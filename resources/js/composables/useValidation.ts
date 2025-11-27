import { ref, reactive } from 'vue';

export interface ValidationRule {
  required?: boolean;
  email?: boolean;
  numeric?: boolean;
  date?: boolean;
  min?: number;
  max?: number;
  minLength?: number;
  maxLength?: number;
  pattern?: RegExp;
  custom?: (value: any) => boolean;
  message?: string;
}

export interface ValidationRules {
  [key: string]: ValidationRule;
}

export interface ValidationErrors {
  [key: string]: string;
}

export function useValidation() {
  const errors = reactive<ValidationErrors>({});

  const validate = (data: Record<string, any>, rules: ValidationRules): boolean => {
    clearErrors();
    let isValid = true;

    for (const field in rules) {
      const value = data[field];
      const rule = rules[field];

      // Required
      if (rule.required) {
        if (value === null || value === undefined || value === '' || 
            (Array.isArray(value) && value.length === 0)) {
          errors[field] = rule.message || 'Este campo es obligatorio';
          isValid = false;
          continue;
        }
      }

      // Skip other validations if empty and not required
      if (!rule.required && (value === null || value === undefined || value === '')) {
        continue;
      }

      // Email
      if (rule.email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
          errors[field] = rule.message || 'Debe ser un correo electrónico válido';
          isValid = false;
          continue;
        }
      }

      // Numeric
      if (rule.numeric) {
        if (isNaN(value) || value === '') {
          errors[field] = rule.message || 'Debe ser un número válido';
          isValid = false;
          continue;
        }
      }

      // Date
      if (rule.date) {
        const dateValue = new Date(value);
        if (isNaN(dateValue.getTime())) {
          errors[field] = rule.message || 'Debe ser una fecha válida';
          isValid = false;
          continue;
        }
      }

      // Min value (for numbers)
      if (rule.min !== undefined && rule.numeric) {
        if (parseFloat(value) < rule.min) {
          errors[field] = rule.message || `Debe ser mayor o igual a ${rule.min}`;
          isValid = false;
          continue;
        }
      }

      // Max value (for numbers)
      if (rule.max !== undefined && rule.numeric) {
        if (parseFloat(value) > rule.max) {
          errors[field] = rule.message || `Debe ser menor o igual a ${rule.max}`;
          isValid = false;
          continue;
        }
      }

      // Min length (for strings)
      if (rule.minLength !== undefined) {
        if (String(value).length < rule.minLength) {
          errors[field] = rule.message || `Debe tener al menos ${rule.minLength} caracteres`;
          isValid = false;
          continue;
        }
      }

      // Max length (for strings)
      if (rule.maxLength !== undefined) {
        if (String(value).length > rule.maxLength) {
          errors[field] = rule.message || `No puede tener más de ${rule.maxLength} caracteres`;
          isValid = false;
          continue;
        }
      }

      // Pattern
      if (rule.pattern) {
        if (!rule.pattern.test(String(value))) {
          errors[field] = rule.message || 'Formato no válido';
          isValid = false;
          continue;
        }
      }

      // Custom validation
      if (rule.custom) {
        if (!rule.custom(value)) {
          errors[field] = rule.message || 'Valor no válido';
          isValid = false;
          continue;
        }
      }
    }

    return isValid;
  };

  const clearErrors = () => {
    Object.keys(errors).forEach(key => delete errors[key]);
  };

  const clearError = (field: string) => {
    delete errors[field];
  };

  const setError = (field: string, message: string) => {
    errors[field] = message;
  };

  const setErrors = (newErrors: ValidationErrors) => {
    clearErrors();
    Object.assign(errors, newErrors);
  };

  const hasError = (field: string): boolean => {
    return !!errors[field];
  };

  const getError = (field: string): string | undefined => {
    return errors[field];
  };

  return {
    errors,
    validate,
    clearErrors,
    clearError,
    setError,
    setErrors,
    hasError,
    getError
  };
}
