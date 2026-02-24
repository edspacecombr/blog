import AdminLayout from '@/components/admin/layouts/AdminLayout';
import SetupWizard from '@/components/admin/SetupWizard';

export default function SetupPage() {
  return (
    <AdminLayout>
      <div className="space-y-6">
        <div className="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8 rounded-lg">
          <h1 className="text-4xl font-bold mb-2">⚙️ Configuração Inicial</h1>
          <p className="text-blue-100">
            Complete os 4 passos para configurar seu blog e começar a publicar conteúdo.
          </p>
        </div>

        <SetupWizard />
      </div>
    </AdminLayout>
  );
}
