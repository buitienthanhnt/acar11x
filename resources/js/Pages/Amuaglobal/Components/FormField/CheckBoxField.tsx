import Checkbox from "@/Components/Checkbox";

interface Props {
  name: string;
  label?: string;
  value: boolean;
  onChange: (type: string, value: any) => void
}
const CheckboxField = ({ name, label, value, onChange }: Props) => {
  return (
    <div className="flex gap-2 items-center text-xl py-2">
      {label}:
      <Checkbox
        className="size-6"
        checked={value}
        onChange={(e: React.ChangeEvent<HTMLInputElement>) => onChange(name, e.target.checked)}
      >
      </Checkbox>
    </div>
  )
}

export default CheckboxField;