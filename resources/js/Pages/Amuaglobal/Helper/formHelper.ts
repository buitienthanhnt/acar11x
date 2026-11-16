import { FormFieldType } from "../types/FormFieldType";

/**
 * 
 * @param formFields 
 * @returns {[string]: any}
 */
export function formartFormData(formFields: FormFieldType[]) {
  return Object.fromEntries(
    formFields.map(field => [field.key, field.value])
  );
}

export function confirmAction(action: () => void, message?: string) {
  if (!window.confirm(message || 'Are you sure?')) {
    return;
  }
  action();
}