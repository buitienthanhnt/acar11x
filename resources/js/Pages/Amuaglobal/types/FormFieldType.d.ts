export type FormFieldType = {
  key: string;
  value: any;
  type: FieldType;
  label?: string;
  placeholder?: string;
  required?: boolean;
  onChange?: (value: string | number | boolean | Blob | any) => void;
  className?: string;
  disabled?: boolean;
  options?: SelectOptionType[];
}

export type SelectOptionType = {
  value: string;
  label: string;
  image?: string;
};

export type FieldType = 'text' | 'textarea' | 'image' | 'file' | 'email' | 'password' | 'number' | 'select' | 'checkbox' | 'radio' | 'boolean' | 'imageChoose';