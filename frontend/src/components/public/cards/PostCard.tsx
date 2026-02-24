'use client';

import Image from 'next/image';
import Link from 'next/link';

interface PostCardProps {
  id: number;
  title: string;
  excerpt: string;
  image?: string;
  category?: string;
  author?: string;
  publishedAt?: string;
  slug: string;
  locale: string;
}

export default function PostCard({
  title,
  excerpt,
  image,
  category,
  author,
  publishedAt,
  slug,
  locale,
}: PostCardProps) {
  return (
    <article className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
      {image && (
        <Link href={`/${locale}${slug}`}>
          <div className="relative w-full h-48 cursor-pointer overflow-hidden">
            {/* F5.10: Lazy loading with sizes and blur placeholder */}
            <Image
              src={image}
              alt={title}
              fill
              sizes="(max-width: 768px) 100vw, (max-width: 1200px) 50vw, 33vw"
              placeholder="blur"
              blurDataURL="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 400 300'%3E%3Crect fill='%23e5e7eb' width='400' height='300'/%3E%3C/svg%3E"
              className="object-cover hover:scale-105 transition-transform duration-300"
              priority={false}
            />
          </div>
        </Link>
      )}

      <div className="p-6">
        {category && (
          <span className="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold mb-3">
            {category}
          </span>
        )}

        <h3 className="text-xl font-bold mb-2 hover:text-blue-600">
          <Link href={`/${locale}${slug}`}>{title}</Link>
        </h3>

        <p className="text-gray-600 mb-4 line-clamp-3">{excerpt}</p>

        <div className="flex justify-between items-center text-sm text-gray-500">
          <div className="flex gap-4">
            {author && <span>Por {author}</span>}
            {publishedAt && <span>{new Date(publishedAt).toLocaleDateString('pt-BR')}</span>}
          </div>
          <Link href={`/${locale}${slug}`} className="text-blue-600 hover:underline font-semibold">
            Ler mais →
          </Link>
        </div>
      </div>
    </article>
  );
}
