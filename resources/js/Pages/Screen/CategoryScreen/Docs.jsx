import TopPageLayout from "@/Layouts/BuildLayout/TopPageLayout";
import { Head, Link, usePage, router } from "@inertiajs/react";

export default function Docs({ categories }) {
    const { url, component } = usePage();

    const onClick = () => {
        router.get('/about', { cat: 10 }, {replace: false})
    }

    if (!categories) {
        return null;
    }

    return (
        <TopPageLayout>
            <Head>
                <title>danh mục</title>
            </Head>
            {/* <div className="bg-white rounded-md p-2 flex">
                <span onClick={onClick} className="bg-blue-gray-100 rounded-md p-2" as="btn">to about</span>
            </div> */}
            <div className="flex-wrap bg-white rounded-lg mt-2">
                {categories.map(function (category, index) {
                    return <Link href={route('category', { category: category.alias })} className="inline-block bg-gray-700 px-3 py-2 rounded-md m-2" key={index}>
                        <span className="text-lg font-bold text-white">{category.name}</span>
                    </Link>
                })}
            </div>
        </TopPageLayout>
    )
}
