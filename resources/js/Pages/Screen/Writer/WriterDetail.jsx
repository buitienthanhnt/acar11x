import { ListItem, Paginate } from "@/Components/Custom";
import SingleLayout from "@/Layouts/BuildLayout/SingleLayout";
import { Head } from "@inertiajs/react";

function WriterDetail({ writer, pages }) {
    const { last_page, current_page, links, data } = pages;

    if (!writer) {
        return;
    }

    return (
        <SingleLayout>
            <Head>
                <title>{writer.name}</title>
            </Head>
            <div className="space-y-2">
                <div className="p-4 bg-white rounded-md flex gap-4">
                    <img src={writer.image_path} className="w-[100px] h-[100px] rounded-full" alt="" />
                    <div>
                        <h4>writer detail: {writer.name}</h4>
                        <h4>phone: {writer.phone}</h4>
                        <h4>description: {writer.description}</h4>
                        <h4>date of birth: {writer.date_of_birth}</h4>
                        <h4>alias: {writer.alias}</h4>
                        <h4>email: {writer.email}</h4>
                        <h4>address: {writer.address}</h4>
                    </div>
                </div>
                {data && <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-4">
                    {data.map((item, index) => {
                        return <ListItem key={index} item={item}></ListItem>
                    })}
                </div>}
                {last_page &&
                    <Paginate pageSize={last_page} currentPage={current_page} links={links} url={window.location.href}></Paginate>
                }
            </div>
        </SingleLayout>
    )
}
export default WriterDetail;
