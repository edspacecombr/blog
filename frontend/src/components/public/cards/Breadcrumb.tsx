'use client';

import Link from 'next/link';

interface BreadcrumbItem {
  label: string;
  href: string;
}

interface BreadcrumbProps {
  items: BreadcrumbItem[];
}

export default function Breadcrumb({ items }: BreadcrumbProps) {
  return (
    <nav className="flex items-center gap-2 text-sm text-gray-600 mb-6">
      {items.map((item, index) => (
        <div key={index} className="flex items-center gap-2">
          <Link href={item.href} className="hover:text-blue-600 hover:underline">
            {item.label}
          </Link>
          {index < items.length - 1 && <span>/</span>}
        </div>
      ))}
    </nav>
  );
}
