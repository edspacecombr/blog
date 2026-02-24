'use client';

import Image from 'next/image';

interface AuthorBoxProps {
  name: string;
  bio?: string;
  image?: string;
  email?: string;
}

export default function AuthorBox({ name, bio, image, email }: AuthorBoxProps) {
  return (
    <div className="bg-gray-50 rounded-lg p-6 border border-gray-200">
      <div className="flex items-start gap-4">
        {image && (
          <Image
            src={image}
            alt={name}
            width={60}
            height={60}
            className="rounded-full"
          />
        )}
        <div className="flex-1">
          <h4 className="font-bold text-lg">{name}</h4>
          {bio && <p className="text-gray-600 text-sm mt-1">{bio}</p>}
          {email && (
            <a href={`mailto:${email}`} className="text-blue-600 text-sm mt-2 hover:underline">
              {email}
            </a>
          )}
        </div>
      </div>
    </div>
  );
}
