import { notFound } from 'next/navigation';
import { getRequestConfig } from 'next-intl/server';

export const locales = ['pt', 'en', 'es'];
export const defaultLocale = 'pt';

export default getRequestConfig(async ({ locale }) => {
  const validLocale = locale || defaultLocale;
  if (!locales.includes(validLocale as any)) notFound();

  return {
    locale: validLocale,
    messages: (await import(`./messages/${validLocale}.json`)).default,
  };
});

export function isValidLocale(locale: string) {
  return locales.includes(locale);
}
