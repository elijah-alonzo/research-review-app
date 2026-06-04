# Multi-Panel Implementation Guide

## 🎯 Overview

Your APMT system now has **three independent Filament panels**, each serving different user roles:

| Panel               | ID          | Path         | Roles              | Purpose                                                |
| ------------------- | ----------- | ------------ | ------------------ | ------------------------------------------------------ |
| **Graduate School** | `app`       | `/app`       | Admin, Dean, Staff | System administration, monitoring, academic management |
| **Registrar**       | `registrar` | `/registrar` | Registrar          | Verify and endorse grading sheets for their program    |
| **Faculty**         | `faculty`   | `/faculty`   | Faculty            | Upload and manage their grading sheets                 |

---

## 📁 File Structure Created

### Panel Providers (Service Providers)

```
app/Providers/Filament/
├── AppPanelProvider.php (Graduate School - default)
├── FacultyPanelProvider.php
└── RegistrarPanelProvider.php
```

### Dashboard Pages

```
app/Filament/Pages/
├── Dashboard.php (Graduate School dashboard - existing)
├── Faculty/
│   └── FacultyDashboard.php (Faculty landing page)
└── Registrar/
    └── RegistrarDashboard.php (Registrar landing page)
```

### Authorization Middleware

```
app/Http/Middleware/
├── EnsureFacultyRole.php
├── EnsureRegistrarRole.php
├── EnsureGraduateSchoolRole.php
└── RedirectBasedOnRole.php (Role-based redirect logic)
```

---

## � Role-Based Login Redirect

### How It Works

When a user logs in, the `RedirectBasedOnRole` middleware **automatically redirects them to their appropriate panel** based on their role:

```
Faculty User
  ↓ (logs in)
redirected to /faculty
  ↓
Faculty Panel loaded

Registrar User
  ↓ (logs in)
redirected to /registrar
  ↓
Registrar Panel loaded

Admin/Dean/Staff User
  ↓ (logs in)
stays at /app (default)
  ↓
Graduate School Panel loaded
```

### Implementation Details

**Middleware File**: `app/Http/Middleware/RedirectBasedOnRole.php`

**Logic**:

1. Checks current request path
2. Verifies user's roles
3. Redirects if user is on wrong panel:
    - Faculty accessing `/app` or `/registrar` → redirect to `/faculty`
    - Registrar accessing `/faculty` → redirect to `/registrar`
    - Admin/Dean/Staff accessing `/faculty` → redirect to `/app`

**Applied to**: All three panel providers' middleware stacks

---

### Graduate School Panel (`/app`)

- **Allowed Roles**: Admin, Dean, Staff
- **Authorization**: `EnsureGraduateSchoolRole` middleware
- **Resources**: All (AcademicYears, Programs, Subjects, RegistrationRequests, Roles, SystemLogs, Users, Account)
- **Features**:
    - Full system administration
    - Monitoring and statistics
    - Permission management via FilamentShield
    - Academic settings and management

### Faculty Panel (`/faculty`)

- **Allowed Roles**: Faculty
- **Authorization**: `EnsureFacultyRole` middleware
- **Resources**:
    - My Grading Sheets (GradingSheetsResource)
    - Account settings
- **Navigation**: Topbar enabled (minimal sidebar)
- **Dashboard**: Shows grading sheet status counts
    - Pending sheets
    - Ready to verify
    - Ready to endorse
    - Submitted sheets

### Registrar Panel (`/registrar`)

- **Allowed Roles**: Registrar
- **Authorization**: `EnsureRegistrarRole` middleware
- **Resources**:
    - Grading Sheet Submissions (GradingSheetApprovalsResource)
    - Account settings
- **Navigation**: Topbar enabled (minimal sidebar)
- **Dashboard**: Shows verification queue status
    - Awaiting verification (to_verify)
    - Awaiting endorsement (to_endorse)
    - Submitted (submitted)
    - Total records in program
- **Data Scoping**: Only shows grading sheets for registrar's assigned program

---

## 👥 Multi-Role Users (Panel Switcher)

### How Panel Switching Works

When a user has **multiple roles** (e.g., Faculty + Admin, or Registrar + Dean):

1. **Panel Switcher Appearance**: A panel selector appears in the UI (typically in the topbar or sidebar)
2. **Available Panels**: User sees only panels they have access to based on their roles
3. **Switching**: User can click to switch between applicable panels
4. **Session Persistence**: Panel selection is remembered during the session

### Example Scenarios

**Scenario 1: Faculty Only**

```
User: john.faculty@uni.com
Roles: Faculty
Panels Available: Faculty only
Behavior: Automatically redirected to /faculty on login
Panel Switcher: Not shown (single panel)
```

**Scenario 2: Faculty + Admin (Multi-Role)**

```
User: admin.faculty@uni.com
Roles: Faculty, Admin
Panels Available: Faculty (/faculty), Graduate School (/app)
Behavior: Redirected to /app (default) on login
Panel Switcher: Shown - can toggle between /faculty and /app
```

**Scenario 3: Registrar + Dean (Multi-Role)**

```
User: dean.registrar@uni.com
Roles: Registrar, Dean
Panels Available: Registrar (/registrar), Graduate School (/app)
Behavior: Redirected to /app (default) on login
Panel Switcher: Shown - can toggle between /registrar and /app
```

---

## 📊 Dashboard Features

### Faculty Dashboard (`/faculty`)

Shows at-a-glance statistics:

```
┌─────────────────────────────────────────┐
│ Pending (0)    Ready to Verify (2)      │
│ Ready to Endorse (1)  Submitted (5)     │
│                                         │
│ [View All Grading Sheets Button]        │
└─────────────────────────────────────────┘
```

**Stats Breakdown:**

- **Pending**: Sheets awaiting upload
- **Ready to Verify**: Faculty has submitted, waiting for registrar verification
- **Ready to Endorse**: Verified by registrar, waiting for dean endorsement
- **Submitted**: Finalized grading sheets

### Registrar Dashboard (`/registrar`)

Shows verification queue statistics:

```
┌──────────────────────────────────────────┐
│ Awaiting Verification (3)  Awaiting     │
│ Endorsement (1)  Submitted (12)        │
│ Total Records (16)                     │
│                                         │
│ [View All Submissions Button]           │
└──────────────────────────────────────────┘
```

**Stats Breakdown:**

- **Awaiting Verification**: Sheets ready for registrar to verify
- **Awaiting Endorsement**: Verified sheets awaiting dean endorsement
- **Submitted**: Finalized sheets
- **Total Records**: All grading sheets in registrar's program

### Graduate School Dashboard

Existing dashboard with filters and full monitoring capabilities.

---

## 🔒 Data Scoping & Security

### Faculty Panel - User Scoping

```php
// GradingSheetsResource filters automatically
->where('user_id', auth()->id())
```

**Result**: Faculty can only see their own grading sheets, even if they somehow access another sheet's URL.

### Registrar Panel - Program Scoping

```php
// GradingSheetApprovalsResource filters automatically
if (user->hasRole('Registrar') && user->program_id) {
    ->where('program_id', user->program_id)
}
```

**Result**: Registrar can only see grading sheets for their assigned program.

### Role-Based Authorization

Each panel has dedicated middleware that verifies:

1. User is authenticated
2. User has the required role(s)
3. Access denied (403) if conditions not met

---

## 🚀 How to Test

### Test Faculty Access

1. Login as Faculty user (email: `faculty@sys.com`, password: `password`)
2. Observe redirect to `/faculty`
3. See Faculty Dashboard with grading sheet statistics
4. Click "View All Grading Sheets" to access the My Grading Sheets resource
5. Only your own grading sheets are visible

### Test Registrar Access

1. Login as Registrar user (email: `registrar@sys.com`, password: `password`)
2. Observe redirect to `/registrar`
3. See Registrar Dashboard with verification queue statistics
4. Click "View All Submissions" to access the Grading Sheet Submissions resource
5. Only sheets for your program are visible

### Test Graduate School Access

1. Login as Admin, Dean, or Staff user
2. Observe redirect to `/app`
3. Access all resources (AcademicYears, Programs, Subjects, etc.)
4. Full system administration features available

### Test Multi-Role Switching (if user has multiple roles)

1. Create a test user with Faculty + Admin roles (or Registrar + Dean)
2. Login with this user
3. Observe panel switcher in the UI
4. Click to switch between available panels
5. Verify each panel shows correct resources

---

## 📝 Database & Models

### Load Model (Grading Sheet)

Located in: `app/Models/Load.php`

```php
class Load extends Model {
    // Relationships
    belongsTo: User (faculty), Program, Subject, AcademicYear

    // Key Fields
    grading_sheet: file/URL
    grading_sheet_status: pending|to_verify|to_endorse|submitted
    user_id: Faculty member's ID
    program_id: Program ID (for scoping)

    // Computed Attribute
    submission_status: Human-readable status
}
```

### Status Workflow

```
pending
  ↓ (Faculty uploads sheet)
to_verify
  ↓ (Registrar verifies)
to_endorse
  ↓ (Dean endorses)
submitted (Final)
```

---

## ⚙️ Configuration Files Modified

### `bootstrap/providers.php`

Added two new panel providers to service provider list:

```php
AppPanelProvider::class,        // Graduate School
FacultyPanelProvider::class,    // Faculty
RegistrarPanelProvider::class,  // Registrar
```

### Resource Configuration

**GradingSheetsResource**:

- `navigationGroup`: 'Grading Sheets'
- `navigationLabel`: 'My Grading Sheets'
- Added `getEloquentQuery()` for user scoping

**GradingSheetApprovalsResource**:

- `navigationGroup`: 'Grading Sheets'
- `navigationLabel`: 'Grading Sheet Submissions'
- Already had `getEloquentQuery()` for program scoping

---

## 🛠️ Middleware Details

### EnsureFacultyRole

- File: `app/Http/Middleware/EnsureFacultyRole.php`
- Checks: `auth()->user()->hasRole('Faculty')`
- Aborts with 403 if user lacks Faculty role

### EnsureRegistrarRole

- File: `app/Http/Middleware/EnsureRegistrarRole.php`
- Checks: `auth()->user()->hasRole('Registrar')`
- Aborts with 403 if user lacks Registrar role

### EnsureGraduateSchoolRole

- File: `app/Http/Middleware/EnsureGraduateSchoolRole.php`
- Checks: `auth()->user()->hasAnyRole(['Admin', 'Dean', 'Staff'])`
- Aborts with 403 if user lacks any of these roles

---

## 🎨 UI Configuration

### Topbar Setting

Faculty and Registrar panels have `->topbar(true)` enabled:

- Hides complex sidebar to reduce visual clutter
- Keeps main navigation in the topbar
- Minimal sidebar still available for account menu

### Collapsibility & Theme

All panels inherit:

- Figtree font
- System logo branding
- Dark mode disabled
- No global search
- Breadcrumbs disabled

---

## 📈 Future Enhancements

Potential improvements:

1. **Post-Login Redirect**: Auto-redirect users to their primary panel based on role priority
2. **Panel Switcher UI**: Customize appearance of panel switcher component
3. **Custom Landing Pages**: Add role-specific widgets/information
4. **Activity Tracking**: Log panel access for audit trails
5. **Notification Center**: Panel-specific notifications

---

## ❓ Troubleshooting

### User can't access panel

- Verify user has correct role assigned
- Check middleware authorization in `app/Http/Middleware/`
- Verify role name matches exactly (case-sensitive): Faculty, Registrar, Admin, Dean, Staff

### Panel switcher not showing

- User likely has only one role
- Panel switcher only appears for multi-role users

### Data not filtered correctly

- Check `getEloquentQuery()` in resource
- Verify `user_id` and `program_id` relationships
- Review ListRecords page for additional filtering logic

### Can't access resources

- Verify permissions in database seeder
- Check Filament Shield policies
- Run: `php artisan shield:generate --all`

---

## 📞 Support

For issues or modifications:

1. Review this guide
2. Check panel provider configuration files
3. Verify middleware logic
4. Test with seeded users
5. Check Laravel logs: `storage/logs/laravel.log`
