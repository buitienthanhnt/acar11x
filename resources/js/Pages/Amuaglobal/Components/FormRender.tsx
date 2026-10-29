import { FunctionComponent } from "react";
import { FormFieldType } from "../types/FormFieldType";
import { CheckboxField, SelectOption, TextInputField } from "./FormField";
import ImageChoose from "./FormField/ImageChoose";
import TextAreaField from "./FormField/TextArea";

interface Props {
  url: string;
  method: 'GET' | "POST" | "PUT" | "DELETE" | "PATCH" | "HEAD";
  formFields: FormFieldType[];
  data: { [key: string]: any };
  onChange?: (key: string, value: any) => void;
  errors?: { [key: string]: string }
}

const FormRender: FunctionComponent<Props> = ({ url, method, formFields, onChange, data }) => {

  if (!formFields) {
    return null;
  }

  return (
    <div className="flex flex-col gap-2">
      {formFields.map((form_field, index: number) => {
        switch (form_field.type) {
          case 'text':
            return (
              <TextInputField
                {...form_field}
                key={index}
                name={form_field.key}
                value={data[form_field.key]}
                onChange={(value) => onChange?.(form_field.key, value)}
              />
            )
          case 'textarea':
            return (
              <TextAreaField
                {...form_field}
                key={index}
                name={form_field.key}
                value={data[form_field.key]}
                onChange={(value) => onChange?.(form_field.key, value)}
              />
            )
          case 'checkbox':
            return (
              <CheckboxField
                name={form_field.key}
                {...form_field}
                key={index}
                label="active"
                value={data[form_field.key]}
                onChange={onChange}
              />
            )
          case 'imageChoose':
            return (
              <ImageChoose
                {...form_field}
                name={form_field.key}
                key={index}
                value={data[form_field.key]}
                onChange={(value) => onChange?.(form_field.key, value)}
              />
            )
          case 'select':
            return (
              <SelectOption
                {...form_field}
                name={form_field.key}
                key={index}
                options={form_field?.options || []}
                onChange={onChange}
                value={data[form_field.key]}
              ></SelectOption>
            )
          default:
            break;
        }
      })
      }
    </div>
  )
}

export default FormRender;