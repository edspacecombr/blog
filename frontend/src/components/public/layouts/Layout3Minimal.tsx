import React from 'react';

interface Layout3MinimalProps {
  children: React.ReactNode;
}

export default function Layout3Minimal({ children }: Layout3MinimalProps) {
  return (
    <div className="flex flex-col min-h-screen bg-white">
      <header className="border-b border-gray-200 sticky top-0 z-50">
        <div className="max-w-2xl mx-auto px-6 py-6 flex justify-between items-center">
          <a href="/" className="text-lg font-semibold">Blog</a>
          <nav className="flex gap-6 text-sm">
            <a href="/blog" className="text-gray-600 hover:text-gray-900">Blog</a>
            <a href="/about" className="text-gray-600 hover:text-gray-900">Sobre</a>
          </nav>
        </div>
      </header>

      <main className="max-w-2xl mx-auto w-full px-6 py-12 flex-1">
        {children}
      </main>

      <footer className="border-t border-gray-200 mt-16">
        <div className="max-w-2xl mx-auto px-6 py-8 text-center text-sm text-gray-500">
          <p>© 2026 Blog Minimalista</p>
        </div>
      </footer>
    </div>
  );
}
