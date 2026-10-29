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