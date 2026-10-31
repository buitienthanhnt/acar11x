import { PageWithLayout } from "@/Pages/Acarglobal/types/ExtendType"
import BaseLayout from "../Layout/BaseLayout"
import { useForm } from "@inertiajs/react"
import { TextInputField, CheckboxField, SelectOption } from "@/Pages/Amuaglobal/Components/FormField";
import PrimaryButton from "@/Components/PrimaryButton"
import { TrashIcon } from "@heroicons/react/24/solid"
import { CategoryDetailType } from "@/Pages/Amuaglobal/types/Category"
import FormRender from "@/Pages/Amuaglobal/Components/FormRender";

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
                <FormRender formFields={formFields} data={data} onChange={setData} style={{
                    display: 'grid', // Set display to grid with 2 column
                    gridTemplateColumns: '1fr 1fr',

                }}></FormRender>
                {/* {categoryTree.length > 0 && (
          <div>
            <label htmlFor="parent_id" className="block text-sm font-medium text-gray-700">Parent Category</label>
            <select
              id="parent_id"
              name="parent_id"
              value={data.parent_id}
              onChange={(e: React.ChangeEvent<HTMLSelectElement>) => setData('parent_id', e.target.value)}
              className="block w-full px-3 py-2 mt-1 bg-white border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
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
