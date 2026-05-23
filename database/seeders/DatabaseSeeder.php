<?php

namespace Database\Seeders;

use App\Enums\AcademicYear;
use App\Models\Load;
use App\Models\Program;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (Permission::query()->count() === 0) {
            Artisan::call('shield:generate', [
                '--all' => true,
                '--option' => 'permissions',
                '--panel' => 'app',
                '--no-interaction' => true,
                '--quiet' => true,
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin']);
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $facultyRole = Role::firstOrCreate(['name' => 'Faculty']);

        $allPermissions = Permission::query()->pluck('name')->all();
        $restrictedPermissions = array_values(array_filter(
            $allPermissions,
            fn (string $permission): bool => Str::contains($permission, [':Role', ':SystemLog', ':SystemLogs'])
        ));
        $adminPermissions = array_values(array_diff($allPermissions, $restrictedPermissions));
        $facultyPermissions = array_values(array_filter(
            $allPermissions,
            fn (string $permission): bool => Str::contains($permission, [':Load', ':GradingSheet', ':Account'])
        ));

        $superAdminRole->syncPermissions($allPermissions);
        $adminRole->syncPermissions($adminPermissions);
        $facultyRole->syncPermissions($facultyPermissions);

        $users = [
            [
                'email' => 'root@spup.com',
                'first_name' => 'Root',
                'middle_initial' => null,
                'last_name' => 'User',
                'contact_number' => '123456789',
                'role' => $superAdminRole,
            ],
            [
                'email' => 'dean@spup.com',
                'first_name' => 'Inicia',
                'middle_initial' => 'C',
                'last_name' => 'Bansig',
                'contact_number' => '123456789',
                'role' => $adminRole,
            ],
            [
                'email' => 'faculty1@spup.com',
                'first_name' => 'Marifel',
                'middle_initial' => 'G',
                'last_name' => 'Kummer',
                'contact_number' => '123456789',
                'role' => $facultyRole,
            ],
            [
                'email' => 'faculty2@spup.com',
                'first_name' => 'Evelyn',
                'middle_initial' => 'E',
                'last_name' => 'Pacquing',
                'contact_number' => '123456789',
                'role' => $facultyRole,
            ],
            [
                'email' => 'faculty3@spup.com',
                'first_name' => 'Charito',
                'middle_initial' => null,
                'last_name' => 'Guillermo',
                'contact_number' => '123456789',
                'role' => $facultyRole,
            ],
            [
                'email' => 'faculty4@spup.com',
                'first_name' => 'Genalin',
                'middle_initial' => null,
                'last_name' => 'Taguiam',
                'contact_number' => '123456789',
                'role' => $facultyRole,
            ],
        ];

        $seededUsers = [];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    ...$userData,
                    'password' => Hash::make('password'),
                ]
            );

            $user->syncRoles([$role]);
            $seededUsers[$user->email] = $user;
        }

        $programs = [
            [
                'code' => 'MIT',
                'name' => 'Master in Information Technology',
                'subjects' => [
                    ['code' => 'MIT-ADB', 'name' => 'Advanced Database Systems'],
                    ['code' => 'MIT-NS', 'name' => 'Network Security'],
                    ['code' => 'MIT-SAD', 'name' => 'Systems Analysis and Design'],
                    ['code' => 'MIT-ITPM', 'name' => 'IT Project Management'],
                ],
            ],
            [
                'code' => 'DIT',
                'name' => 'Doctor in Information Technology',
                'subjects' => [
                    ['code' => 'DIT-ARM', 'name' => 'Advanced Research Methods'],
                    ['code' => 'DIT-DM', 'name' => 'Data Mining'],
                    ['code' => 'DIT-CM', 'name' => 'Cybersecurity Management'],
                    ['code' => 'DIT-ET', 'name' => 'Emerging Technologies'],
                ],
            ],
            [
                'code' => 'MAP',
                'name' => 'Master of Arts in Psychology',
                'subjects' => [
                    ['code' => 'MAP-PA', 'name' => 'Psychological Assessment'],
                    ['code' => 'MAP-AP', 'name' => 'Abnormal Psychology'],
                    ['code' => 'MAP-CT', 'name' => 'Counseling Techniques'],
                    ['code' => 'MAP-RS', 'name' => 'Research Statistics'],
                ],
            ],
            [
                'code' => 'PHD-PSY',
                'name' => 'Doctor of Philosophy in Psychology',
                'subjects' => [
                    ['code' => 'PHD-PSY-AP', 'name' => 'Advanced Psychotherapy'],
                    ['code' => 'PHD-PSY-CP', 'name' => 'Cognitive Psychology'],
                    ['code' => 'PHD-PSY-BR', 'name' => 'Behavioral Research'],
                    ['code' => 'PHD-PSY-CS', 'name' => 'Clinical Supervision'],
                ],
            ],
            [
                'code' => 'MBA',
                'name' => 'Master in Business Administration',
                'subjects' => [
                    ['code' => 'MBA-FM', 'name' => 'Financial Management'],
                    ['code' => 'MBA-MM', 'name' => 'Marketing Management'],
                    ['code' => 'MBA-HRM', 'name' => 'Human Resource Management'],
                    ['code' => 'MBA-SP', 'name' => 'Strategic Planning'],
                ],
            ],
            [
                'code' => 'DBA',
                'name' => 'Doctor in Business Administration',
                'subjects' => [
                    ['code' => 'DBA-OL', 'name' => 'Organizational Leadership'],
                    ['code' => 'DBA-BA', 'name' => 'Business Analytics'],
                    ['code' => 'DBA-CG', 'name' => 'Corporate Governance'],
                    ['code' => 'DBA-AMT', 'name' => 'Advanced Management Theory'],
                ],
            ],
            [
                'code' => 'MAED',
                'name' => 'Master of Arts in Education',
                'subjects' => [
                    ['code' => 'MAED-CD', 'name' => 'Curriculum Development'],
                    ['code' => 'MAED-EL', 'name' => 'Educational Leadership'],
                    ['code' => 'MAED-AL', 'name' => 'Assessment of Learning'],
                    ['code' => 'MAED-ER', 'name' => 'Educational Research'],
                ],
            ],
            [
                'code' => 'EDD',
                'name' => 'Doctor of Education',
                'subjects' => [
                    ['code' => 'EDD-AEP', 'name' => 'Advanced Educational Policy'],
                    ['code' => 'EDD-IL', 'name' => 'Instructional Leadership'],
                    ['code' => 'EDD-QR', 'name' => 'Qualitative Research'],
                    ['code' => 'EDD-SA', 'name' => 'School Administration'],
                ],
            ],
            [
                'code' => 'MSN',
                'name' => 'Master of Science in Nursing',
                'subjects' => [
                    ['code' => 'MSN-ANP', 'name' => 'Advanced Nursing Practice'],
                    ['code' => 'MSN-HE', 'name' => 'Healthcare Ethics'],
                    ['code' => 'MSN-NR', 'name' => 'Nursing Research'],
                    ['code' => 'MSN-CHN', 'name' => 'Community Health Nursing'],
                ],
            ],
            [
                'code' => 'PHD-NURS',
                'name' => 'Doctor of Philosophy in Nursing',
                'subjects' => [
                    ['code' => 'PHD-NURS-NTD', 'name' => 'Nursing Theory Development'],
                    ['code' => 'PHD-NURS-HS', 'name' => 'Healthcare Systems'],
                    ['code' => 'PHD-NURS-ACP', 'name' => 'Advanced Clinical Practice'],
                    ['code' => 'PHD-NURS-RS', 'name' => 'Research Seminar'],
                ],
            ],
            [
                'code' => 'MPA',
                'name' => 'Master in Public Administration',
                'subjects' => [
                    ['code' => 'MPA-PP', 'name' => 'Public Policy'],
                    ['code' => 'MPA-LG', 'name' => 'Local Governance'],
                    ['code' => 'MPA-FA', 'name' => 'Fiscal Administration'],
                    ['code' => 'MPA-HRM', 'name' => 'Human Resource Management'],
                ],
            ],
            [
                'code' => 'DPA',
                'name' => 'Doctor in Public Administration',
                'subjects' => [
                    ['code' => 'DPA-GD', 'name' => 'Governance and Development'],
                    ['code' => 'DPA-PSL', 'name' => 'Public Sector Leadership'],
                    ['code' => 'DPA-PA', 'name' => 'Policy Analysis'],
                    ['code' => 'DPA-AL', 'name' => 'Administrative Law'],
                ],
            ],
            [
                'code' => 'MSCE',
                'name' => 'Master of Science in Civil Engineering',
                'subjects' => [
                    ['code' => 'MSCE-SE', 'name' => 'Structural Engineering'],
                    ['code' => 'MSCE-GE', 'name' => 'Geotechnical Engineering'],
                    ['code' => 'MSCE-CM', 'name' => 'Construction Management'],
                    ['code' => 'MSCE-HY', 'name' => 'Hydraulics'],
                ],
            ],
            [
                'code' => 'DENG',
                'name' => 'Doctor of Engineering',
                'subjects' => [
                    ['code' => 'DENG-AEM', 'name' => 'Advanced Engineering Mathematics'],
                    ['code' => 'DENG-EI', 'name' => 'Engineering Innovation'],
                    ['code' => 'DENG-SI', 'name' => 'Sustainable Infrastructure'],
                    ['code' => 'DENG-RM', 'name' => 'Research Methods'],
                ],
            ],
            [
                'code' => 'MSCS',
                'name' => 'Master of Science in Computer Science',
                'subjects' => [
                    ['code' => 'MSCS-AI', 'name' => 'Artificial Intelligence'],
                    ['code' => 'MSCS-ML', 'name' => 'Machine Learning'],
                    ['code' => 'MSCS-ALG', 'name' => 'Algorithms'],
                    ['code' => 'MSCS-SE', 'name' => 'Software Engineering'],
                ],
            ],
        ];

        foreach ($programs as $programData) {
            $program = Program::updateOrCreate(
                ['code' => $programData['code']],
                [
                    'name' => $programData['name'],
                    'description' => $programData['description'] ?? null,
                    'is_active' => true,
                ]
            );

            foreach ($programData['subjects'] as $subjectData) {
                $subjectName = $subjectData['name'];

                if (Subject::where('name', $subjectName)->exists()) {
                    $subjectName = $programData['code'].' - '.$subjectName;
                }

                Subject::updateOrCreate(
                    [
                        'code' => $subjectData['code'],
                    ],
                    [
                        'program_id' => $program->id,
                        'name' => $subjectName,
                        'description' => $subjectData['description'] ?? null,
                        'is_active' => true,
                    ]
                );
            }
        }

        $facultyUsers = User::role('Faculty')->get();
        $subjects = Subject::query()->orderBy('id')->get();
        $terms = ['First Term', '2nd Term', '3rd Term'];

        foreach ($facultyUsers as $index => $faculty) {
            $baseOffset = $index * 3;

            for ($i = 0; $i < 3; $i++) {
                $subject = $subjects->get(($baseOffset + $i) % $subjects->count());

                Load::updateOrCreate(
                    [
                        'program_id' => $subject->program_id,
                        'subject_id' => $subject->id,
                        'term' => $terms[$i],
                        'user_id' => $faculty->id,
                    ],
                    [
                        'academic_year' => AcademicYear::current()->value,
                        'grading_sheet' => null,
                        'submission_deadline' => now()->addWeeks(2),
                    ]
                );
            }
        }
    }
}
