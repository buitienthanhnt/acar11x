import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";

export default function TextAreaField({
  name, value, isFocused, onChange, required, error, label, type = 'text', ...props
}: {
  name: string,
  value: string,
  isFocused?: boolean,
  onChange?: (value: string) => void,
  required?: boolean,
  error?: string,
  label?: string,
  type?: string,
  [key: string]: any,
}) {

  return (
    <div className="w-full">
      <InputLabel
        htmlFor={label}
        value={label}
        className={`text-xl ${required ? "after:content-['*'] after:ml-0.5 after:text-red-500" : ''}`} children={undefined} />
      <textarea
        id={name}
        type={type}
        name={name}
        value={value}
        className="block w-full mt-1 rounded-md"
        autoComplete={name}
        isFocused={isFocused}
        rows={3}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => onChange?.(e.target.value)}
        required={required}
        {...props}
      />
      <InputError message={error} className="mt-2" />
    </div>
  )
}
