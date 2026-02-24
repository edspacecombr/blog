'use client';

import Link from 'next/link';

interface PaginationProps {
  currentPage: number;
  totalPages: number;
  baseUrl: string;
  locale: string;
}

export default function Pagination({
  currentPage,
  totalPages,
  baseUrl,
  locale,
}: PaginationProps) {
  const getUrl = (page: number) => `/${locale}${baseUrl}?page=${page}`;

  return (
    <div className="flex justify-center items-center gap-2 mt-8">
      {currentPage > 1 && (
        <Link
          href={getUrl(currentPage - 1)}
          className="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100"
        >
          ← Anterior
        </Link>
      )}

      {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
        <Link
          key={page}
          href={getUrl(page)}
          className={`px-4 py-2 rounded ${
            page === currentPage
              ? 'bg-blue-600 text-white'
              : 'border border-gray-300 hover:bg-gray-100'
          }`}
        >
          {page}
        </Link>
      ))}

      {currentPage < totalPages && (
        <Link
          href={getUrl(currentPage + 1)}
          className="px-4 py-2 border border-gray-300 rounded hover:bg-gray-100"
        >
          Próximo →
        </Link>
      )}
    </div>
  );
}
