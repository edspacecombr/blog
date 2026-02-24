import type { Metadata } from 'next';
import { notFound } from 'next/navigation';
import { locales } from '@/i18n';
import Layout1Clean from '@/components/public/layouts/Layout1Clean';

export async function generateStaticParams() {
  return locales.map((locale) => ({ locale }));
}

export const metadata: Metadata = {
  title: 'Blog Multilíngue',
  description: 'Um blog moderno e rápido',
};

export default function RootLayout({
  children,
  params: { locale },
}: {
  children: React.ReactNode;
  params: { locale: string };
}) {
  if (!locales.includes(locale)) {
    notFound();
  }

  return (
    <html lang={locale}>
      <body>
        <Layout1Clean>{children}</Layout1Clean>
      </body>
    </html>
  );
}
