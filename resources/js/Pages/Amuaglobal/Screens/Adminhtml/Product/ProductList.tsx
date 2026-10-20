import { Head, Link } from "@inertiajs/react"
import { PaginateInterface } from "../../../types/Paginate"
import { Paginate } from "../../../Components"
import BaseLayout from "../Layout/BaseLayout"

const ProductList = ({ products }: { products: PaginateInterface }) => {

  return (
    <BaseLayout>
      <Head title="Product List" />
      <div className="container mx-auto">
       <div className="flex space-x-2">
         <Link
          href={'/adminhtml/amua-product/create'}
          className="bg-green-500 text-white px-4 py-2 rounded-md mb-4 inline-block w-fit">
          Create Product
        </Link>
       </div>

        <h1 className="text-2xl font-bold mb-4">Product List</h1>
        {products?.data?.map(product => <ProductItem key={product.id} product={product} />)}
        <Paginate
          pageSize={products?.last_page}
          currentPage={products?.current_page}
          url={window.location.href}
          pageName={'page'}
        />
      </div>
    </BaseLayout>
  )
}

export const ProductItem = ({ product, onSelectProduct }: { product: any; onSelectProduct?: (product: any) => void }) => {
  return (
    <div className="border border-gray-700 rounded-md mb-1 flex gap-4" onClick={() => onSelectProduct?.(product)}>
      <img src={product.image_path} className="size-24 rounded-md object-cover" alt="" />
      <div className="flex flex-col justify-between">
        <p className="font-semibold text-lg">{product.name}</p>
        <p className="text-xl font-bold text-green-500">{product.price} vnđ</p>
      </div>
    </div>
  )
}

export default ProductList
