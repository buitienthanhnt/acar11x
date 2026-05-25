import { useForm } from "@inertiajs/react";
import TextInputField from "../Components/FormField/TextInputField";
import PrimaryButton from "@/Components/PrimaryButton";

const ProductCreate = () => {
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

  const submit = (e) => {
    e.preventDefault();
    post('/adminhtml/product/store');
  }

  return (
    <div className="container p-4 bg-gray-300 mx-auto">
      <h1>Product Create</h1>
      <form onSubmit={submit}>
        <div className="flex gap-2">
          <TextInputField
            name="name"
            label="product name"
            value={data.name}
            onChange={(value) => setData('name', value)}
            error={errors.name}
            required
          ></TextInputField>

          <TextInputField
            name="alias"
            label="product alias"
            value={data.alias}
            onChange={(value) => setData('alias', value)}
            error={errors.alias}
          ></TextInputField>
        </div>

        <TextInputField
          name="sku"
          label="product sku"
          value={data.sku}
          onChange={(value) => setData('sku', value)}
          error={errors.sku}
          required
        ></TextInputField>

        <input type="file" onChange={e => setData('image', e.target.files[0])} />

        <TextInputField
          name="description"
          label="product description"
          value={data.description}
          onChange={(value) => setData('description', value)}
          error={errors.description}
          rows={4}
        ></TextInputField>

        <div className="flex gap-2">
          <TextInputField
            name="base_price"
            label="product base price"
            value={data.base_price}
            onChange={(value) => setData('base_price', value)}
            type="number"
            min={0}
            step={1}
            required
            error={errors.base_price}
          ></TextInputField>

          <TextInputField
            name="price"
            label="product price"
            value={data.price}
            onChange={(value) => setData('price', value)}
            type="number"
            min={0}
            step={1}
            required
            error={errors.price}
          ></TextInputField>
          <TextInputField
            name="qty"
            label="product quantity"
            value={data.qty}
            onChange={(value) => setData('qty', value)}
            type="number"
            min={0}
            step={1}
            required
            error={errors.qty}
          ></TextInputField>
        </div>
        <PrimaryButton disabled={processing} className="mt-2">Save</PrimaryButton>
      </form>
    </div>
  )
}

export default ProductCreate;