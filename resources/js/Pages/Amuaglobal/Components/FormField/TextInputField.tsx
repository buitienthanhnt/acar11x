import InputError from "@/Components/InputError";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";

export default function TextInputField({
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
      <TextInput
        id={name}
        type={type}
        name={name}
        value={value}
        className="block w-full mt-1"
        autoComplete={name}
        isFocused={isFocused}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => onChange?.(type === 'file' ? e.target.files[0] : e.target.value)}
        required={required}
        {...props}
      />
      <InputError message={error} className="mt-2" />
    </div>
  )
}
