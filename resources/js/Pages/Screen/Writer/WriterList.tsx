import { Paginate } from "@/Components/Custom";
import SingleLayout from "@/Layouts/BuildLayout/SingleLayout";
import { Head, Link } from "@inertiajs/react";

interface WriterList {
    current_page: Number;
    last_page: Number;
    data: any;
    links?: any;
}

export default function WriterList({ current_page, last_page, data }: WriterList) {

    if (!data) {
        return null;
    }

    return (
        <SingleLayout>
            <Head>
                <title>tác giả</title>
            </Head>
            <div className="flex flex-wrap bg-white rounded-xl p-4 gap-4">
                {data.map((item, index) => {
                    return <WriterItem writer={item} key={index}></WriterItem>
                })}
            </div>
            <Paginate pageSize={last_page} currentPage={current_page} url={window.location.href}></Paginate>
        </SingleLayout>
    )
}


function WriterItem({ writer }) {

    return (
        <Link href={route("writerDetail", { id: writer.id })} className="bg-slate-400 p-2 flex-1 rounded-xl space-y-2">
            <div className="flex flex-row py-1">
                <h4 className="font-semibold text-xl text-white">{writer.name}</h4>
                <h4 className="text-gray-800 font-semibold text-xl underline px-1">({writer.alias})</h4>
            </div>
            <img src={writer.image_path} alt="" className="rounded-md hover:resize-2" />
            <div>
                <p className="text-gray-200 float-end">{writer.date_of_birth.substring(0, 10)}</p>
                <p className=" underline font-semibold">{writer.phone}</p>
                <p className=" text-gray-200">{writer.description}</p>
            </div>
        </Link>
    )
}
