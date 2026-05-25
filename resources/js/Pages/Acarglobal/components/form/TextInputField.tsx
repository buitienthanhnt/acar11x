
const TextInputField = ({ name, value, label, type, displayClass, error, ...props }: {
  name: string;
  value?: any;
  label?: string;
  type?: string;
  displayClass?: string;
  error?: string;
  [key: string]: any,
}) => {
  return (
    <div className={`flex space-x-2 w-full items-center ${displayClass}`}>
      {label && <div className="w-auto flex-1 flex-col">
        {label && <p className="font-semibold text-info text-md w-auto flex-1">{label}:</p>}
        {error && <p className="text-sm text-red-600">{error}</p>}
      </div>}

      <input type={type || "text"} name={name} value={value} {...props} className={
        'flex rounded-md bg-transparent border-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 w-3/5 ' +
        props?.className +
        (props?.disabled ? ' bg-gray-100 cursor-not-allowed' : '')
      } />
    </div>
  )
}

export default TextInputField;