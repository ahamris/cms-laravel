<?php

namespace Database\Seeders;

use App\Helpers\Variable;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->count() > 0) {
            return;
        }

        // Get the application domain from APP_URL (for first account only)
        $appUrl = config('app.url', 'http://localhost');
        $host = parse_url($appUrl, PHP_URL_HOST);
        $domain = $this->extractMainDomain($host);
        $domainBasedEmail = 'admin@' . $domain;
        
        // Loop through all accounts in DEFAULT_ACCOUNTS
        foreach (Variable::DEFAULT_ACCOUNTS as $index => $account) {
            [$name, $lastName, $originalEmail, $password, $role] = $account;
            
            // First account uses domain-based email, others use their original email
            $email = $index === 0 ? $domainBasedEmail : $originalEmail;
            
            // Create user account
            $user = User::query()->firstOrCreate([
                'email' => $email,
            ], [
                'name' => $name,
                'last_name' => $lastName,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]);
            
            if ($user) {
                $user->assignRole($role);
            }
        }
    }

    /**
     * Extract the main domain from a host, removing subdomains.
     *
     * @param string|null $host
     * @return string
     */
    private function extractMainDomain(?string $host): string
    {
        // If host is null or empty, fallback to demo.com
        if (empty($host) || $host === 'localhost' || $host === '127.0.0.1') {
            return 'demo.com';
        }

        // Handle IP addresses
        if (filter_var($host, FILTER_VALIDATE_IP)) {
            return 'demo.com';
        }

        // Split the host into parts
        $parts = explode('.', $host);
        
        // If it's a simple domain (e.g., "example.com"), return as is
        if (count($parts) <= 2) {
            return $host;
        }

        // Known two-part TLDs (e.g., .co.uk, .com.au, .org.uk)
        $twoPartTlds = ['co.uk', 'com.au', 'org.uk', 'net.au', 'gov.uk', 'ac.uk','com.tr'];
        
        // Check if the last two parts form a known two-part TLD
        $lastTwoParts = strtolower($parts[count($parts) - 2] . '.' . $parts[count($parts) - 1]);
        
        if (in_array($lastTwoParts, $twoPartTlds)) {
            // For two-part TLDs, take the last 3 parts (e.g., example.co.uk)
            return implode('.', array_slice($parts, -3));
        }
        
        // For standard TLDs, take the last 2 parts (e.g., example.com)
        return implode('.', array_slice($parts, -2));
    }
}
