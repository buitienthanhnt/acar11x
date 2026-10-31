import { useForm, Head } from "@inertiajs/react";
import PrimaryButton from "@/Components/PrimaryButton";
import BaseLayout from "../Layout/BaseLayout";
import FormRender from "@/Pages/Amuaglobal/Components/FormRender";

const ProductCreate = ({ formFields }) => {
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
        post('/adminhtml/product/store');
    }

    return (
        <div className="container p-4 mx-auto bg-gray-300">
            <h1>Product Create</h1>
            <form onSubmit={submit}>
                <FormRender formFields={formFields} data={data} onChange={setData} style={{
                    display: 'grid', // Set display to grid with 2 column
                    gridTemplateColumns: '1fr 1fr',

                }}></FormRender>
                <PrimaryButton disabled={processing} className="mt-2">Save</PrimaryButton>
            </form>
        </div>
    )
}
{/* @ts-ignore-next-line */ }
ProductCreate.layout = page => <BaseLayout children={page} title="create product"></BaseLayout>
export default ProductCreate;
