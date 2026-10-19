import { PageWithLayout } from "@/Pages/Acarglobal/types/ExtendType"
import BaseLayout from "../Layout/BaseLayout"
import { useForm } from "@inertiajs/react"
import TextInputField from "@/Pages/Amuaglobal/Components/FormField/TextInputField"
import PrimaryButton from "@/Components/PrimaryButton"

interface Props {
    formFields: any[]
}
const CategoryCreate: PageWithLayout<Props> = () => {

    const { data, setData, errors, post, processing } = useForm({
        name: '',
        alias: '',
        description: '',
        base_price: '',
        price: '',
        image: null,
        category_ids: [],
        sku: '',
        qty: '',
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

                <TextInputField
                    name="sku"
                    label="category sku"
                    value={data.sku}
                    onChange={(value) => setData('sku', value)}
                    error={errors.sku}
                    required
                ></TextInputField>

                <input type="file" className="my-2" onChange={(e: React.ChangeEvent<HTMLInputElement>) => setData('image', e?.target?.files[0])} />

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
