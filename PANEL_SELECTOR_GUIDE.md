# Panel Selector Login Feature

## 🎯 Overview

Users now have a **panel selector dropdown** on the login page, allowing them to choose which panel they want to access **before logging in**. This replaces the automatic redirect system with explicit user choice.

---

## 🎨 Login Form Update

### New Form Field: Panel Selector

The login form now includes a "Select Panel" dropdown with three options:

```
┌─────────────────────────────────────────┐
│ Email or Contact Number: [____________] │
│ Password: [__________________________]  │
│ Select Panel: [Graduate School ▼]      │
│  ☐ Remember me                         │
│ [Login Button]                          │
└─────────────────────────────────────────┘
```

### Panel Options Available

| Option | Value | Description |
|--------|-------|-------------|
| **Graduate School** | `app` | Default option - System administration, monitoring, academic management |
| **Faculty** | `faculty` | Upload and manage grading sheets |
| **Registrar** | `registrar` | Verify and endorse grading sheets |

---

## 🔄 Login Flow with Panel Selection

### Step-by-Step Process

```
1. User visits login page (any panel path)
   ↓
2. Sees dropdown with three panel options
   ↓
3. User selects desired panel
   ↓
4. User enters email/contact + password
   ↓
5. System authenticates credentials
   ↓
6. System validates panel access:
   - Faculty role → can access 'faculty' panel
   - Registrar role → can access 'registrar' panel
   - Admin/Dean/Staff → can access 'app' panel
   ↓
7a. If selection is VALID → redirect to selected panel
    Example: Faculty user selects 'faculty' → /faculty
   ↓
7b. If selection is INVALID → redirect to allowed panel
    Example: Faculty user selects 'app' → /faculty (no access)
    Example: Registrar selects 'faculty' → /registrar (no access)
   ↓
8. Panel loads with authenticated session
```

---

## 💻 Technical Implementation

### Files Modified

1. **`app/Filament/Pages/Auth/Login.php`** - Updated Login page class
   - Added `getFormComponents()` method with panel selector field
   - Added `getCredentialsFromFormData()` to store selected panel in session
   - Added `getAuthenticatedRedirectUrl()` to handle panel validation and redirect

2. **`app/Providers/Filament/AppPanelProvider.php`** - Graduate School panel
   - Already used custom Login class ✓

3. **`app/Providers/Filament/FacultyPanelProvider.php`** - Faculty panel
   - Changed `->login()` to `->login(Login::class)` to use custom login

4. **`app/Providers/Filament/RegistrarPanelProvider.php`** - Registrar panel
   - Changed `->login()` to `->login(Login::class)` to use custom login

5. **`app/Http/Middleware/RedirectBasedOnRole.php`** - Redirect middleware
   - Enhanced to respect selected panel during initial login
   - Added fallback redirects if selection is invalid

### Login Page Component Code

```php
protected function getFormComponents(): array
{
    return [
        $this->getEmailFormComponent(),
        $this->getPasswordFormComponent(),
        $this->getRememberFormComponent(),
        Select::make('panel')
            ->label('Select Panel')
            ->options([
                'app' => 'Graduate School',
                'faculty' => 'Faculty',
                'registrar' => 'Registrar',
            ])
            ->required()
            ->native(false)
            ->default('app')
            ->helperText('Choose which panel you want to access'),
    ];
}
```

### Validation Logic

```php
protected function getAuthenticatedRedirectUrl(): string
{
    $user = Auth::user();
    $selectedPanel = session()->pull('selected_panel', 'app');

    // Validate user has access to selected panel
    $canAccessFaculty = $user->hasRole('Faculty');
    $canAccessRegistrar = $user->hasRole('Registrar');
    $canAccessApp = $user->hasAnyRole(['Admin', 'Dean', 'Staff']);

    // Check if user can access selected panel
    $isValidSelection = match ($selectedPanel) {
        'faculty' => $canAccessFaculty,
        'registrar' => $canAccessRegistrar,
        'app' => $canAccessApp,
        default => false,
    };

    // If invalid, redirect to default allowed panel
    if (! $isValidSelection) {
        if ($canAccessFaculty) return '/faculty';
        if ($canAccessRegistrar) return '/registrar';
        if ($canAccessApp) return '/app';
    }

    // Redirect to selected panel
    return match ($selectedPanel) {
        'faculty' => '/faculty',
        'registrar' => '/registrar',
        default => '/app',
    };
}
```

---

## 🧪 Test Scenarios

### Scenario 1: Faculty User - Correct Panel Selection

```
User: faculty@sys.com (Faculty role)
Selected Panel: Faculty
Expected Behavior: 
  ✓ Login succeeds
  ✓ Redirect to /faculty
  ✓ Faculty Panel loads with My Grading Sheets
```

### Scenario 2: Faculty User - Invalid Panel Selection

```
User: faculty@sys.com (Faculty role)
Selected Panel: Graduate School
Expected Behavior:
  ✓ Login succeeds
  ✓ System detects invalid selection (Faculty can't access /app)
  ✓ Redirect to /faculty (allowed panel)
  ✓ Faculty Panel loads
```

### Scenario 3: Registrar User - Correct Panel Selection

```
User: registrar@sys.com (Registrar role)
Selected Panel: Registrar
Expected Behavior:
  ✓ Login succeeds
  ✓ Redirect to /registrar
  ✓ Registrar Panel loads with Grading Sheet Submissions
```

### Scenario 4: Registrar User - Invalid Panel Selection

```
User: registrar@sys.com (Registrar role)
Selected Panel: Faculty
Expected Behavior:
  ✓ Login succeeds
  ✓ System detects invalid selection (Registrar can't access /faculty)
  ✓ Redirect to /registrar (allowed panel)
  ✓ Registrar Panel loads
```

### Scenario 5: Admin User - Any Panel Selection

```
User: admin@sys.com (Admin role)
Selected Panel: Faculty, Registrar, or Graduate School
Expected Behavior:
  ✓ Login succeeds
  ✓ Redirect to selected panel
  ✓ For Faculty: Redirects to /faculty (Admin can't access)
     → Actually redirects to /app (Admin default)
  ✓ For Registrar: Redirects to /registrar (Admin can't access)
     → Actually redirects to /app (Admin default)
  ✓ For Graduate School: Redirect to /app ✓
```

### Scenario 6: Multi-Role User (Faculty + Admin)

```
User: dual.role@sys.com (Faculty + Admin roles)
Login Path: /app/login
Selected Panel: Faculty
Expected Behavior:
  ✓ Login succeeds
  ✓ Redirect to /faculty
  ✓ Faculty Panel loads
  ✓ Panel switcher visible (can switch to /app)
```

---

## 🛡️ Security Features

1. **Role Validation**: Panel selection is validated against user's roles
   - Invalid selections are automatically corrected
   - User cannot access panels they're not authorized for

2. **Session Storage**: Selected panel is stored in session
   - Pulled after authentication
   - Single-use (pulled = removed)
   - Prevents session pollution

3. **Middleware Failsafe**: `RedirectBasedOnRole` middleware provides backup
   - If user manually navigates to wrong panel URL
   - Automatically redirects to correct panel
   - Defense-in-depth approach

4. **Default Fallback**: If selection is invalid/missing
   - System defaults to 'app' (Graduate School)
   - User gets redirected to their allowed panel

---

## 🎛️ Access Control

### Faculty Role
- **Can Select**: Faculty panel ✓
- **Cannot Select**: Registrar, Graduate School
- **Default Redirect**: → Faculty panel

### Registrar Role
- **Can Select**: Registrar panel ✓
- **Cannot Select**: Faculty, Graduate School
- **Default Redirect**: → Registrar panel

### Admin/Dean/Staff Roles
- **Can Select**: Graduate School (app) ✓
- **Cannot Select**: Faculty, Registrar
- **Default Redirect**: → Graduate School panel

### Multi-Role Users (e.g., Faculty + Admin)
- **Can Select**: Any panel they have a role for
- **Example**: Faculty + Admin can select Faculty OR Graduate School
- **Panel Switcher**: Visible in UI to manually switch between accessible panels

---

## 📱 User Experience

### Benefits

1. **Explicit Choice**: Users choose their panel before login (clearer intent)
2. **Faster Navigation**: Direct access to their specific panel
3. **Visual Clarity**: Dropdown shows all available options
4. **Error Prevention**: Invalid selections are corrected automatically
5. **Flexibility**: Multi-role users can choose their preferred panel

### UX Flow

```
Welcome Page
   ↓ [Login Button]
Login Page
   ↓ Dropdown: "Select Panel"
User selects desired panel
   ↓ Enter credentials
   ↓ [Login]
Validation checks panel access
   ↓
Redirect to correct panel
   ↓
Dashboard loads
```

---

## 🔧 Customization

To modify panel options or labels:

**File**: `app/Filament/Pages/Auth/Login.php`

```php
Select::make('panel')
    ->label('Select Panel')  // Change label
    ->options([
        'app' => 'Graduate School',
        'faculty' => 'Faculty',
        'registrar' => 'Registrar',
    ])  // Modify options here
    ->required()
    ->native(false)
    ->default('app')  // Change default
    ->helperText('Choose which panel you want to access'),  // Change helper text
```

---

## ✅ Deployment Checklist

- [x] Add panel selector to login form
- [x] Implement panel validation logic
- [x] Update all panel providers to use custom Login page
- [x] Enhance redirect middleware with session handling
- [x] Clear application caches
- [x] Test all user roles and selection combinations
- [x] Verify error handling for invalid selections

---

## 🐛 Troubleshooting

### Issue: Panel dropdown not showing on login

**Solution**:
```bash
php artisan cache:clear
php artisan view:clear
php artisan route:cache
```

### Issue: Wrong panel after login

**Solution**:
1. Check user role assignments: `php artisan tinker`
   ```php
   User::find(id)->roles
   ```
2. Verify role matches panel access rules
3. Check middleware order in panel providers

### Issue: Multi-role users can't see panel switcher

**Solution**:
1. Ensure user has multiple roles assigned
2. Verify all accessible panels are registered
3. Check Filament panel switcher settings

---

## 📞 Support & Further Enhancements

Potential future improvements:

1. **Panel Preferences**: Remember user's last selected panel
2. **Custom Redirect**: Different default panels per role priority
3. **Status Messages**: Show "Redirecting to your panel..." during redirect
4. **Restricted Panels**: Hide panels from dropdown if user can't access
5. **Deep Linking**: Share direct panel login URLs with specific pre-selection
