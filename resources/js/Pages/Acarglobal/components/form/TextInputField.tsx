import { FormField } from "../../types/FormField"

const TextInputField = ({ name, value, label, type, ...props }: any) => {
  return (
    <div className="flex space-x-2 w-full items-center justify-between">
      {label && <span className="font-semibold text-info text-md w-1/5">{label}:</span>}

      <input type={type || "text"} name={name} value={value} {...props} className={
        'flex rounded-md bg-transparent border-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-3/5 ' +
        props?.className +
        (props?.disabled ? ' bg-gray-100 cursor-not-allowed' : '')
      } />
    </div>
  )
}

export default TextInputField;