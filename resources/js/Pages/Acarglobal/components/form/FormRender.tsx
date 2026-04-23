import { FunctionComponent, useCallback } from "react";
import { FormField } from "../../types/FormField";
import TextInputField from "./TextInputField";
import { useForm } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";

interface Props {
  form_fields: FormField[],
  url?: string,
}

const FormRender: FunctionComponent<Props> = ({ form_fields, url }) => {
  /**
   * Convert form_fields to object
   * get form fields
   */
  let _form_fields: { [key: string]: string | any } = {};
  form_fields.forEach((field) => {
    _form_fields[field.name] = field.value;
  })
  /**
   * init form data
   */
  const { data, setData, post, processing, errors, reset, recentlySuccessful } = useForm(_form_fields);

  const onSubmit = useCallback((e) => {
    e.preventDefault();
    console.log(data);
    post(url, );
  }, [data])

  return (
    <form className="px-2 py-4 flex justify-center bg-white shadow-md overflow-hidden sm:rounded-lg " onSubmit={onSubmit} autoComplete="true">
      <div className="w-full space-y-2 sm:max-w-md md:max-w-2xl">
        {form_fields.map((field, index: number) => {
          switch (field.type) {
            case 'text':
              return <TextInputField
                {...field} 
                key={index}
                value={data[field.name]}
                autoComplete={field.name}
                onChange={(e) => setData(field.name, e.target.value)}
                placeholder={field.placeholder ?? field.name}
              />
            case 'number':
              return <TextInputField {...field} key={index}></TextInputField>
          }
        })}
        <PrimaryButton className="w-full content-center text-center justify-center" disabled={processing}>
          Gui di
        </PrimaryButton>
      </div>
    </form>
  );
}

export default FormRender;