import { PageWithLayout } from "@/Pages/Acarglobal/types/ExtendType"
import BaseLayout from "../Layout/BaseLayout"
import { useForm } from "@inertiajs/react"
import TextInputField from "@/Pages/Amuaglobal/Components/FormField/TextInputField"
import PrimaryButton from "@/Components/PrimaryButton"
import Checkbox from "@/Components/Checkbox"
import { TrashIcon } from "@heroicons/react/24/solid"

interface Props {
  formFields: any[]
}
const CategoryCreate: PageWithLayout<Props> = () => {

  const { data, setData, errors, post, processing } = useForm({
    name: '',
    alias: '',
    description: '',
    active: true,
    image_path: null,
    category_ids: [],
  })

  const submit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    post('/adminhtml/amua-category/store');
  }

  return (
    <div className="container p-4 mx-auto bg-gray-300">
      <form onSubmit={submit}>
        <div className="flex gap-2">
          <TextInputField
            name="name"
            label="category name"
            value={data.name}
            onChange={(value) => setData('name', value)}
            error={errors.name}
            required
          ></TextInputField>

          <TextInputField
            name="alias"
            label="category alias"
            value={data.alias}
            onChange={(value) => setData('alias', value)}
            error={errors.alias}
          ></TextInputField>
        </div>

        <div className="flex gap-2 items-center text-xl py-2">
          active:
          <Checkbox className="size-6" checked={data.active} onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('active', e.target.checked)}></Checkbox>
        </div>
        <div className="flex gap-2 item-center relative">
          {data.image_path && <TrashIcon className="size-6 absolute right-4 top-4" onClick={() => setData('image_path', null)}></TrashIcon>}
          {/* @ts-ignore */}
          <input type="file" className="my-2" onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('image_path', e?.target?.files[0])} />
          {data?.image_path && <img src={URL.createObjectURL(data.image_path)} alt="" className="w-48 h-48 rounded-full object-center" />}
        </div>

        <TextInputField
          name="description"
          label="category description"
          value={data.description}
          onChange={(value) => setData('description', value)}
          error={errors.description}
          rows={4}
        ></TextInputField>
        <PrimaryButton disabled={processing} className="mt-2">Save</PrimaryButton>
      </form>
    </div>
  )
}

CategoryCreate.layout = page => <BaseLayout title="create category" children={page}></BaseLayout>

export default CategoryCreate;
