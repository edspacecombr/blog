import React from 'react';

interface Layout2MagazineProps {
  children: React.ReactNode;
}

export default function Layout2Magazine({ children }: Layout2MagazineProps) {
  return (
    <div className="flex flex-col min-h-screen bg-white">
      <header className="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-12">
        <div className="container mx-auto px-4">
          <h1 className="text-4xl font-bold mb-4">Magazine</h1>
          <p className="text-lg text-blue-100">Histórias inspiradoras</p>
        </div>
      </header>

      <main className="container mx-auto px-4 py-12 flex-1">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-8">
          <div className="lg:col-span-3">{children}</div>
          <aside className="space-y-6">
            <div className="bg-blue-50 rounded-lg p-6">
              <h3 className="font-bold">Destaque</h3>
            </div>
          </aside>
        </div>
      </main>

      <footer className="bg-gray-100 border-t border-gray-200 mt-12">
        <div className="container mx-auto px-4 py-8 text-center">
          <p className="text-gray-600">© 2026 Magazine</p>
        </div>
      </footer>
    </div>
  );
}
