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
        <Link href={route("writerDetail", { id: writer.id })}
            className="bg-slate-400 p-2 flex-1 rounded-xl space-y-2 border-gray-900 hover:outline outline-2 outline-gray-800">
            <div className="flex flex-row py-1 items-end">
                <h4 className="font-semibold text-xl text-black hover:text-blue-500">{writer.name}</h4>
                <h4 className="text-gray-600 font-semibold text-md px-1">({writer.alias})</h4>
            </div>
            <img src={writer.image_path} alt="" className="rounded-md" />
            <div>
                <p className="text-green-500 float-end">{writer.date_of_birth.substring(0, 10)}</p>
                <p className="underline font-semibold">{writer.phone}</p>
                <p className=" text-orange-700">{writer.description}</p>
            </div>
        </Link>
    )
}
