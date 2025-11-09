import { ListItem, Paginate } from "@/Components/Custom";
import SingleLayout from "@/Layouts/BuildLayout/SingleLayout";
import { Head, usePage } from "@inertiajs/react";

export default function PagesByCategory() {
    const { category, pages: { current_page, last_page, data, links } } = usePage().props;

    if (!category) {
        return null;
    }

    return (
        <SingleLayout>
            <Head>
                <title>{category.name}</title>
                 <meta head-key="description" name="description" content={category.description} />
            </Head>
            <div className="space-y-2">
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
