import { router } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

export default function TopBar() {
  return (
    <div className="flex p-2 bg-gray-500 space-x-2">
      {/* @ts-ignore-next-line */}
      <Button variant="filled" onClick={() => {
        router.get('/adminhtml/amua-category')
      }}>category</Button>
      {/* @ts-ignore-next-line */}
      <Button variant="filled" onClick={() => {
        router.get('/adminhtml/amua-product')
      }}>product</Button>
    </div>
  )
}
