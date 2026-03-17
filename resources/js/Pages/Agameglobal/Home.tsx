
import { Head, Link, router } from "@inertiajs/react";
import { Button } from "@material-tailwind/react";

export default function Home() {

    return (
        <div className="grid grid-cols-2 flex-1 p-4 bg-blue-gray-100 flex-col gap-2 container mx-auto">
            <Head title="Agame home" />
            <Button variant="filled" onClick={()=>{
                router.visit(route('agameglobal.dem.nguoc'));
            }}>
                <Link className="text-blue-500">
                    Đếm ngược thời gian
                </Link>
            </Button>

            <Button variant="outline" onClick={()=>{
                router.get(route('agameglobal.bam.gio.don'))
            }}>
                <Link className="text-blue-500">
                    Thời gian tăng dần
                </Link>
            </Button>

            <Button variant="outline" onClick={()=>{
                router.get('agame/bam-gio')
            }}>
                <p className="text-blue-500">
                    Thời gian chuyển tiếp
                </p>
            </Button>
        </div>
    )
}
