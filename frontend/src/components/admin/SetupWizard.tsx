'use client';

import { useState } from 'react';
import { useSetupWizard } from '@/hooks/admin/useSetupWizard';
import {
  SetupStep1,
  SetupStep2,
  SetupStep3,
  SetupStep4,
} from '@/lib/validators';

export default function SetupWizard() {
  const { currentStep, isLoading, errors, nextStep, prevStep, submit } = useSetupWizard();

  const [step1Data, setStep1Data] = useState<SetupStep1>({
    blog_name: '',
    blog_url: '',
    blog_description: '',
    blog_email: '',
  });

  const [step2Data, setStep2Data] = useState<SetupStep2>({
    logo_url: '',
    favicon_url: '',
    primary_color: '#3B82F6',
    secondary_color: '#10B981',
  });

  const [step3Data, setStep3Data] = useState<SetupStep3>({
    default_language: 'pt',
    active_languages: ['pt'],
  });

  const [step4Data, setStep4Data] = useState<SetupStep4>({
    blog_niche: '',
    active_layout: 'Layout1Clean',
  });

  const handleStep1Next = () => nextStep(step1Data);
  const handleStep2Next = () => nextStep(step2Data);
  const handleStep3Next = () => nextStep(step3Data);
  const handleStep4Submit = () => submit(step4Data);

  return (
    <div className="max-w-2xl mx-auto">
      {/* Progress Bar */}
      <div className="mb-8">
        <div className="flex justify-between mb-4">
          {[1, 2, 3, 4].map((step) => (
            <div key={step} className="flex flex-col items-center">
              <div
                className={`w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg ${
                  currentStep >= step
                    ? 'bg-blue-600 text-white'
                    : 'bg-gray-300 text-gray-600'
                }`}
              >
                {step}
              </div>
              <p className="text-sm mt-2 text-gray-600">
                {step === 1 && 'Informações'}
                {step === 2 && 'Design'}
                {step === 3 && 'Idiomas'}
                {step === 4 && 'Nicho'}
              </p>
            </div>
          ))}
        </div>
        <div className="h-2 bg-gray-300 rounded-full overflow-hidden">
          <div
            className="h-full bg-blue-600 transition-all duration-300"
            style={{ width: `${(currentStep / 4) * 100}%` }}
          />
        </div>
      </div>

      {/* Step 1: Informações do Blog */}
      {currentStep === 1 && (
        <div className="bg-white p-8 rounded-lg shadow-md">
          <h2 className="text-2xl font-bold mb-6">Informações do Blog</h2>

          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Nome do Blog *
              </label>
              <input
                type="text"
                value={step1Data.blog_name}
                onChange={(e) => setStep1Data({ ...step1Data, blog_name: e.target.value })}
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                  errors.blog_name ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                }`}
                placeholder="Meu Blog Incrível"
              />
              {errors.blog_name && <p className="text-red-500 text-sm mt-1">{errors.blog_name}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                URL do Blog *
              </label>
              <input
                type="url"
                value={step1Data.blog_url}
                onChange={(e) => setStep1Data({ ...step1Data, blog_url: e.target.value })}
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                  errors.blog_url ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                }`}
                placeholder="https://meublog.com"
              />
              {errors.blog_url && <p className="text-red-500 text-sm mt-1">{errors.blog_url}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Descrição do Blog *
              </label>
              <textarea
                value={step1Data.blog_description}
                onChange={(e) => setStep1Data({ ...step1Data, blog_description: e.target.value })}
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                  errors.blog_description ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                }`}
                rows={4}
                placeholder="Descreva seu blog..."
              />
              {errors.blog_description && (
                <p className="text-red-500 text-sm mt-1">{errors.blog_description}</p>
              )}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Email de Contato *
              </label>
              <input
                type="email"
                value={step1Data.blog_email}
                onChange={(e) => setStep1Data({ ...step1Data, blog_email: e.target.value })}
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                  errors.blog_email ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                }`}
                placeholder="contato@meublog.com"
              />
              {errors.blog_email && <p className="text-red-500 text-sm mt-1">{errors.blog_email}</p>}
            </div>
          </div>

          <div className="flex justify-end gap-4 mt-6">
            <button
              onClick={handleStep1Next}
              disabled={isLoading}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
            >
              Próximo →
            </button>
          </div>
        </div>
      )}

      {/* Step 2: Design Visual */}
      {currentStep === 2 && (
        <div className="bg-white p-8 rounded-lg shadow-md">
          <h2 className="text-2xl font-bold mb-6">Design Visual</h2>

          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                URL da Logo
              </label>
              <input
                type="url"
                value={step2Data.logo_url}
                onChange={(e) => setStep2Data({ ...step2Data, logo_url: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                placeholder="https://..."
              />
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                URL do Favicon
              </label>
              <input
                type="url"
                value={step2Data.favicon_url}
                onChange={(e) => setStep2Data({ ...step2Data, favicon_url: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                placeholder="https://..."
              />
            </div>

            <div className="grid grid-cols-2 gap-4">
              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Cor Primária *
                </label>
                <div className="flex gap-2">
                  <input
                    type="color"
                    value={step2Data.primary_color}
                    onChange={(e) =>
                      setStep2Data({ ...step2Data, primary_color: e.target.value })
                    }
                    className="w-14 h-10 border border-gray-300 rounded cursor-pointer"
                  />
                  <input
                    type="text"
                    value={step2Data.primary_color}
                    onChange={(e) =>
                      setStep2Data({ ...step2Data, primary_color: e.target.value })
                    }
                    className="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-medium text-gray-700 mb-1">
                  Cor Secundária *
                </label>
                <div className="flex gap-2">
                  <input
                    type="color"
                    value={step2Data.secondary_color}
                    onChange={(e) =>
                      setStep2Data({ ...step2Data, secondary_color: e.target.value })
                    }
                    className="w-14 h-10 border border-gray-300 rounded cursor-pointer"
                  />
                  <input
                    type="text"
                    value={step2Data.secondary_color}
                    onChange={(e) =>
                      setStep2Data({ ...step2Data, secondary_color: e.target.value })
                    }
                    className="flex-1 px-4 py-2 border border-gray-300 rounded-lg"
                  />
                </div>
              </div>
            </div>
          </div>

          <div className="flex justify-between gap-4 mt-6">
            <button
              onClick={prevStep}
              className="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
            >
              ← Anterior
            </button>
            <button
              onClick={handleStep2Next}
              disabled={isLoading}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
            >
              Próximo →
            </button>
          </div>
        </div>
      )}

      {/* Step 3: Idiomas */}
      {currentStep === 3 && (
        <div className="bg-white p-8 rounded-lg shadow-md">
          <h2 className="text-2xl font-bold mb-6">Configurar Idiomas</h2>

          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-3">
                Idioma Padrão *
              </label>
              <select
                value={step3Data.default_language}
                onChange={(e) => setStep3Data({ ...step3Data, default_language: e.target.value })}
                className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
              >
                <option value="pt">Português</option>
                <option value="en">English</option>
                <option value="es">Español</option>
              </select>
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-3">
                Idiomas Ativos *
              </label>
              <div className="space-y-2">
                {['pt', 'en', 'es'].map((lang) => (
                  <label key={lang} className="flex items-center gap-3">
                    <input
                      type="checkbox"
                      checked={step3Data.active_languages.includes(lang)}
                      onChange={(e) => {
                        const newLangs = e.target.checked
                          ? [...step3Data.active_languages, lang]
                          : step3Data.active_languages.filter((l) => l !== lang);
                        setStep3Data({ ...step3Data, active_languages: newLangs });
                      }}
                      className="w-4 h-4"
                    />
                    <span className="text-gray-700">
                      {lang === 'pt' && 'Português'}
                      {lang === 'en' && 'English'}
                      {lang === 'es' && 'Español'}
                    </span>
                  </label>
                ))}
              </div>
            </div>
          </div>

          <div className="flex justify-between gap-4 mt-6">
            <button
              onClick={prevStep}
              className="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
            >
              ← Anterior
            </button>
            <button
              onClick={handleStep3Next}
              disabled={isLoading}
              className="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:bg-gray-400"
            >
              Próximo →
            </button>
          </div>
        </div>
      )}

      {/* Step 4: Nicho e Layout */}
      {currentStep === 4 && (
        <div className="bg-white p-8 rounded-lg shadow-md">
          <h2 className="text-2xl font-bold mb-6">Nicho e Layout</h2>

          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium text-gray-700 mb-1">
                Nicho do Blog *
              </label>
              <input
                type="text"
                value={step4Data.blog_niche}
                onChange={(e) => setStep4Data({ ...step4Data, blog_niche: e.target.value })}
                className={`w-full px-4 py-2 border rounded-lg focus:outline-none ${
                  errors.blog_niche ? 'border-red-500' : 'border-gray-300 focus:border-blue-500'
                }`}
                placeholder="Ex: Tecnologia, Viagens, Lifestyle"
              />
              {errors.blog_niche && <p className="text-red-500 text-sm mt-1">{errors.blog_niche}</p>}
            </div>

            <div>
              <label className="block text-sm font-medium text-gray-700 mb-3">
                Layout Padrão *
              </label>
              <div className="space-y-2">
                {['Layout1Clean', 'Layout2Magazine', 'Layout3Minimal'].map((layout) => (
                  <label key={layout} className="flex items-center gap-3 p-3 border rounded-lg hover:bg-gray-50">
                    <input
                      type="radio"
                      name="layout"
                      value={layout}
                      checked={step4Data.active_layout === layout}
                      onChange={(e) =>
                        setStep4Data({ ...step4Data, active_layout: e.target.value as any })
                      }
                      className="w-4 h-4"
                    />
                    <div>
                      <p className="font-medium text-gray-900">
                        {layout === 'Layout1Clean' && '🎨 Clean Editorial'}
                        {layout === 'Layout2Magazine' && '📰 Magazine Style'}
                        {layout === 'Layout3Minimal' && '✨ Minimal Blog'}
                      </p>
                      <p className="text-sm text-gray-500">
                        {layout === 'Layout1Clean' && 'Design limpo e profissional'}
                        {layout === 'Layout2Magazine' && 'Estilo revista com múltiplas colunas'}
                        {layout === 'Layout3Minimal' && 'Foco em leitura, sem distrações'}
                      </p>
                    </div>
                  </label>
                ))}
              </div>
            </div>
          </div>

          <div className="flex justify-between gap-4 mt-6">
            <button
              onClick={prevStep}
              className="px-6 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400"
            >
              ← Anterior
            </button>
            <button
              onClick={handleStep4Submit}
              disabled={isLoading}
              className="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-gray-400"
            >
              {isLoading ? '⏳ Salvando...' : '✅ Concluir Setup'}
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
