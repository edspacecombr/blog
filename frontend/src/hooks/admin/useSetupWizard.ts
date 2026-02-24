import { useState } from 'react';
import { useRouter } from 'next/navigation';
import { toast } from 'react-toastify';
import {
  setupStep1Schema,
  setupStep2Schema,
  setupStep3Schema,
  setupStep4Schema,
  SetupStep1,
  SetupStep2,
  SetupStep3,
  SetupStep4,
} from '@/lib/validators';

interface SetupFormData {
  step1?: SetupStep1;
  step2?: SetupStep2;
  step3?: SetupStep3;
  step4?: SetupStep4;
}

export function useSetupWizard() {
  const router = useRouter();
  const [currentStep, setCurrentStep] = useState(1);
  const [isLoading, setIsLoading] = useState(false);
  const [formData, setFormData] = useState<SetupFormData>({});
  const [errors, setErrors] = useState<Record<string, string>>({});

  const validateStep = async (step: number, data: any) => {
    try {
      setErrors({});
      switch (step) {
        case 1:
          setupStep1Schema.parse(data);
          setFormData((prev) => ({ ...prev, step1: data }));
          break;
        case 2:
          setupStep2Schema.parse(data);
          setFormData((prev) => ({ ...prev, step2: data }));
          break;
        case 3:
          setupStep3Schema.parse(data);
          setFormData((prev) => ({ ...prev, step3: data }));
          break;
        case 4:
          setupStep4Schema.parse(data);
          setFormData((prev) => ({ ...prev, step4: data }));
          break;
      }
      return true;
    } catch (error: any) {
      const fieldErrors: Record<string, string> = {};
      if (error.errors) {
        error.errors.forEach((err: any) => {
          fieldErrors[err.path.join('.')] = err.message;
        });
      }
      setErrors(fieldErrors);
      return false;
    }
  };

  const nextStep = async (data: any) => {
    const isValid = await validateStep(currentStep, data);
    if (isValid && currentStep < 4) {
      setCurrentStep(currentStep + 1);
      toast.success(`Passo ${currentStep} concluído`);
    }
    return isValid;
  };

  const prevStep = () => {
    if (currentStep > 1) {
      setCurrentStep(currentStep - 1);
    }
  };

  const submit = async (finalData: any) => {
    const isValid = await validateStep(currentStep, finalData);
    if (!isValid) return false;

    setIsLoading(true);
    try {
      const completeData = {
        ...formData,
        [currentStep === 4 ? 'step4' : `step${currentStep}`]: finalData,
      };

      const response = await fetch('/api/v1/setup/complete', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
        },
        body: JSON.stringify(completeData),
      });

      if (!response.ok) {
        throw new Error('Erro ao salvar configurações');
      }

      toast.success('Setup concluído com sucesso!');
      router.push('/admin');
      return true;
    } catch (error: any) {
      toast.error(error.message || 'Erro ao salvar configurações');
      return false;
    } finally {
      setIsLoading(false);
    }
  };

  return {
    currentStep,
    isLoading,
    formData,
    errors,
    nextStep,
    prevStep,
    submit,
  };
}
