'use client';

import Link from 'next/link';

interface CategoryBadgeProps {
  name: string;
  slug: string;
  locale: string;
  count?: number;
}

export default function CategoryBadge({ name, slug, locale, count }: CategoryBadgeProps) {
  return (
    <Link href={`/${locale}/category/${slug}`}>
      <span className="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors cursor-pointer">
        {name}
        {count !== undefined && <span className="text-xs text-gray-500">({count})</span>}
      </span>
    </Link>
  );
}
