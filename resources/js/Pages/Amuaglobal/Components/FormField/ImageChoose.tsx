import { isObject, isString } from "lodash";
import { FormFieldType } from "../../types/FormFieldType";
import InputLabel from "@/Components/InputLabel";
import { TrashIcon } from "@heroicons/react/24/solid";
import { useCallback } from "react";

const ImageChoose = ({ label, value, onChange, name, required }: Omit<FormFieldType, 'key'> & { name: string }) => {
  const imagePath = isObject(value) ? URL.createObjectURL(value as unknown as Blob) : isString(value) ? value : '';

  const onClearImage = useCallback(() => {
    onChange?.(null);
  }, [])

  return (
    <div className="space-y-2 text-xl items-center">
      {label && <InputLabel
        htmlFor={name}
        value={label}
        className={`text-xl ${required ? "after:content-['*'] after:ml-0.5 after:text-red-500" : ''}`} children={undefined} />}
      <div className='flex gap-2 space-x-2'>
        {imagePath && <div className="flex relative p-4">
          <img
            src={imagePath}
            className="w-24 h-24 object-center rounded-md"
          />
          <TrashIcon className="size-8 absolute right-[-8px] top-[-8px]" onClick={onClearImage}></TrashIcon>
        </div>
        }
        <input
          className="font-semibold"
          type="file"
          name={name}
          required={required}
          // @ts-ignore-next-line
          onChange={(e: React.ChangeEvent<HTMLInputElement>) => onChange?.(e?.target?.files[0])}
        />
      </div>
    </div>
  )
}

export default ImageChoose