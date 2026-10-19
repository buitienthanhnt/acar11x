import { router } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

export default function TopBar() {
    return (
        <div className="flex p-2 bg-gray-500">
            <Button variant="filled" onClick={()=>{
                router.get('/adminhtml/amua-category/create')
            }}>create category</Button>
        </div>
    )
}
