import { PageWithLayout } from "@/Pages/Acarglobal/types/ExtendType"
import BaseLayout from "../Layout/BaseLayout"
import { useForm } from "@inertiajs/react"
import { TextInputField, CheckboxField, SelectOption } from "@/Pages/Amuaglobal/Components/FormField";
import PrimaryButton from "@/Components/PrimaryButton"
import { TrashIcon } from "@heroicons/react/24/solid"
import { CategoryDetailType } from "@/Pages/Amuaglobal/types/Category"

interface Props {
  formFields: any[],
  categoryTree: CategoryDetailType[],
}
const CategoryCreate: PageWithLayout<Props> = ({ formFields }) => {

  const { data, setData, errors, post, processing } = useForm({
    name: '',
    alias: '',
    description: '',
    active: true,
    image: null,
    parent_id: 0,
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
          />

          <TextInputField
            name="alias"
            label="category alias"
            value={data.alias}
            onChange={(value) => setData('alias', value)}
            error={errors.alias}
          />
        </div>

        <CheckboxField name='active' value={data.active} label="active" onChange={setData}></CheckboxField>
        <div className="flex gap-2 item-center relative">
          {data.image && <TrashIcon className="size-6 absolute right-4 top-4" onClick={() => setData('image', null)}></TrashIcon>}
          {/* @ts-ignore */}
          <input type="file" className="my-2" onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('image', e?.target?.files[0])} />
          {data?.image && <img src={URL.createObjectURL(data.image)} alt="" className="w-48 h-48 rounded-full object-center" />}
        </div>

        <TextInputField
          name="description"
          label="category description"
          value={data.description}
          onChange={(value) => setData('description', value)}
          error={errors.description}
          rows={4}
        />
        {formFields.length > 0 && (
          formFields.map((field, index: number) => {
            switch (field.type) {
              case 'select':
                return <SelectOption
                  key={index}
                  name="parent_id"
                  label="category"
                  data={field.options}
                  value={data.parent_id}
                  onChange={setData}
                ></SelectOption>
              default:
                return null;
            }
          })
        )}

        {/* {categoryTree.length > 0 && (
          <div>
            <label htmlFor="parent_id" className="block text-sm font-medium text-gray-700">Parent Category</label>
            <select
              id="parent_id"
              name="parent_id"
              value={data.parent_id}
              onChange={(e: React.ChangeEvent<HTMLSelectElement>) => setData('parent_id', e.target.value)}
              className="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            >
              <option value="">Select a category</option>
              <CategoryOptions categories={categoryTree} sufix=""></CategoryOptions>
            </select>
          </div>
        )} */}
        <PrimaryButton disabled={processing} className="mt-2">Save</PrimaryButton>
      </form>
    </div>
  )
}

const CategoryOptions = ({ categories, sufix = '' }: { categories: CategoryDetailType[], sufix?: string }) => {
  if (!categories) {
    return null;
  }

  return (
    <>
      {categories.map((category) => (
        <>
          <option key={category.id} value={category.id}>
            {sufix}{category.name}
          </option>
          {category?.childrents && category?.childrents.length > 0 && (
            <CategoryOptions categories={category.childrents} sufix={sufix + "___"}></CategoryOptions>
          )}
        </>
      ))}
    </>
  )
}

CategoryCreate.layout = page => <BaseLayout title="create category" children={page}></BaseLayout>

export default CategoryCreate;
