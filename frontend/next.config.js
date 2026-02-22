/** @type {import('next').NextConfig} */
const nextConfig = {
  i18n: {
    locales: ['en', 'es', 'pt'],
    defaultLocale: 'en',
  },
  reactStrictMode: true,
  swcMinify: true,
  images: {
    unoptimized: false,
    formats: ['image/avif', 'image/webp'],
  },
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL || 'http://localhost:8000',
  },
};

module.exports = nextConfig;
