<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run(): void
    {
        $divisions = [
            'Office of Civil Defense',
            'Office of Civil Defense Deputy for Operations',
            'Office of Civil Defense Deputy for Administration',
            'Office of Civil Defense Deputy for Civil Defense and Strategy Management',
            'Office of the Head Executive Assistant',
            'Legal and Legislative Office',
            'Internal Monitoring and Evaluation Office',
            'Administrative Service',
            'Human Resource Management and Development Division',
            'General Service Division',
            'Procurement Management Division',
            'Planning and Financial Management Service',
            'Budget Division',
            'Accounting Division',
            'Planning Division',
            'Information and Communication Technology Service',
            'Operations Service',
            'Early Warning Division',
            'Logistics Management Division',
            'Response Division',
            'Early Recovery Division',
            'Accreditation and Mobilization Division',
            'Disaster Resilience Service',
            'DRRM Standards and Monitoring Division',
            'Prevention and Mitigation Division',
            'Disaster Preparedness Service',
            'Domestic Cooperation Division',
            'Readiness Division',
            'Strategic Communication',
            'Rehabilitation and Recovery Management Service',
            'Post Disaster Evaluation and Management Division',
            'DRRM Fund Management Division',
            'Recovery Implementation and Monitoring Division',
            'Strategy Management Service',
            'International Cooperations Division (ICD)',
            'Risk Governance and Special Division',
            'Civil Defense Service',
            'Civil Defense Enhancement Division',
            'Civil Defense Force Development Division',
            'Civil Defense and Disaster Management Training Institute',
            'Curriculum Development Division',
            'Training and Education Division',
            'Knowledge Management Division',
        ];

        foreach ($divisions as $name) {
            Division::firstOrCreate(['name' => strtoupper($name)]);
        }
    }
}
