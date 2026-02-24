<?php

namespace App\Seeds;

/**
 * F7.1: Seed for creating essential system pages
 */
class EssentialPagesSeeder
{
    /**
     * Generate essential pages for the blog
     */
    public static function generate(): array
    {
        return [
            [
                'slug' => 'privacy-policy',
                'title' => 'Privacy Policy',
                'type' => 'privacy',
                'content' => self::getPrivacyPolicyTemplate(),
            ],
            [
                'slug' => 'terms-of-service',
                'title' => 'Terms of Service',
                'type' => 'terms',
                'content' => self::getTermsTemplate(),
            ],
            [
                'slug' => 'about-us',
                'title' => 'About Us',
                'type' => 'about',
                'content' => self::getAboutTemplate(),
            ],
            [
                'slug' => 'contact',
                'title' => 'Contact Us',
                'type' => 'contact',
                'content' => self::getContactTemplate(),
            ],
        ];
    }

    private static function getPrivacyPolicyTemplate(): string
    {
        return <<<'HTML'
<h1>Privacy Policy</h1>
<p>We respect your privacy. This page explains how we collect and use information.</p>
<h2>Information We Collect</h2>
<p>We collect information you provide directly and information about your usage.</p>
<h2>How We Use Information</h2>
<p>We use information to provide, improve, and personalize our services.</p>
<h2>Contact Us</h2>
<p>For privacy inquiries, contact privacy@example.com</p>
HTML;
    }

    private static function getTermsTemplate(): string
    {
        return <<<'HTML'
<h1>Terms of Service</h1>
<p>By using our website, you agree to these terms.</p>
<h2>License to Use Website</h2>
<p>We grant you a limited license to access and use our website.</p>
<h2>Disclaimer of Warranties</h2>
<p>This website is provided 'as is' without any representations or warranties.</p>
<h2>Limitation of Liability</h2>
<p>In no event shall our company be liable for any indirect damages.</p>
HTML;
    }

    private static function getAboutTemplate(): string
    {
        return <<<'HTML'
<h1>About Us</h1>
<p>Welcome to our blog platform dedicated to sharing knowledge and insights.</p>
<h2>Our Mission</h2>
<p>We aim to provide high-quality content across multiple languages.</p>
<h2>Our Values</h2>
<ul>
  <li>Quality content</li>
  <li>Accessibility</li>
  <li>Community</li>
</ul>
HTML;
    }

    private static function getContactTemplate(): string
    {
        return <<<'HTML'
<h1>Contact Us</h1>
<p>Have a question? We'd love to hear from you.</p>
<p>Email: contact@example.com</p>
<p>Use the contact form below to get in touch:</p>
HTML;
    }
}
