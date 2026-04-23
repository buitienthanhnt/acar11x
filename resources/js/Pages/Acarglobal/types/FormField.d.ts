export type FormField = {
  name: string;
  value: string;
  type: string;
  label?: string;
  placeholder?: string;
  required?: boolean;
  onChange?: (value: string) => void;
  className?: string;
  disabled?: boolean;
}