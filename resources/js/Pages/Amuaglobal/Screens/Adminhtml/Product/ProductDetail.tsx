import { GalleryInterface } from "@/Pages/Amuaglobal/types/Gallery";

export default function ProductDetail({ product }: { product: any }) {

  return (
    <div className="container mx-auto p-4">
      <h1 className="text-2xl font-bold mb-4">{product.name}</h1>
      <img src={product.image} alt={product.name} className="w-64 h-64 object-cover mb-4" />
      <p className="text-xl font-bold text-green-500 mb-2">{product.price} vnđ</p>
      <p className="text-gray-700">{product.description}</p>
      {/* {JSON.stringify(product)} */}
      <ProductGalleries galleries={product.galleries}></ProductGalleries>
    </div>
  )
}

const ProductGalleries = ({ galleries }: { galleries: GalleryInterface[] }) => {
  if (!galleries.length) {
    return null;
  }

  return (
    <div className="grid grid-cols-4 py-4">
      {galleries.map((gallery, index) => <img
        key={index}
        src={gallery.path}
        className="w-64 h-64 object-cover mb-4 rounded-md" />
      )}
    </div>
  )
}