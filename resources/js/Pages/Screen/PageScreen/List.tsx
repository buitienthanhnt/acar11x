import TopPageLayout from "@/Layouts/BuildLayout/TopPageLayout";
import { Head, router } from "@inertiajs/react";
import { Paginate, ListItem, RandomHorizon, CenterCategory, } from "@/Components/Custom";
import { DropdownMenu } from "@/Components/Custom/DropdownMenu";
import { useCallback, useMemo } from "react";
import { ArrowPathIcon } from "@heroicons/react/24/solid";
import SwiperImage from "@/Components/Custom/SwiperImage";

const List = ({ paginate: { current_page, last_page, data, links, }, filters }) => {

    return (
        <>
            <Head title="list page">
                {/* add css inline for page.(<Head> tag must be in: <> tag; not in <div> tag) */}
                <style>
                    {`.demo{background-color: red;}`}
                </style>
            </Head>
            <div className="space-y-2">
                <PageFilter filters={filters}></PageFilter>
                {data && <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-4">
                    {data.map((item, index) => {
                        return <ListItem key={index} item={item}></ListItem>
                    })}
                </div>}
                {current_page && <Paginate pageSize={last_page} currentPage={current_page} links={links} url={window.location.href}></Paginate>}
                {/* <Banner layout={''} page={randoms[2]}></Banner> */}
                <RandomHorizon></RandomHorizon>
                <CenterCategory></CenterCategory>
                <div className="grid bg-white rounded-md grid-cols-6 gap-x-2 p-1">
                    <div className="bg-blue-gray-400 col-span-6 md:col-span-4">
                        <SwiperImage></SwiperImage>
                    </div>
                    <div className="bg-gray-300 md:col-span-2 flex justify-center p-1 rounded-md">
                        <span className="font-bold text-xl text-blue-gray-700">Quảng cáo!</span>
                    </div>
                </div>
            </div>
        </>
    )
}

const PageFilter = ({ filters = [] }) => {
    /**
     * action for select filter item.
     */
    const onChange = useCallback((type, item) => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get(type) == item.value) {
            // remove filter param.
            router.get(window.location.href, {
                [type]: undefined,
                page: undefined, // reset page
            });
            return;
        }
        router.get(window.location.href, {
            [type]: item.value,
            page: undefined, // reset page
        });
    }, []);

    const isSelectedFilter = useMemo(() => {
        const selected = filters.filter(filter => filter.data.filter(i => i.selected).length);
        return !!selected.length;
    }, [])

    const onReset = useCallback(() => {
        router.get(window.location.pathname,);
        return;
    }, [])

    if (!filters.length) { return null; }

    return (
        <>
            <Head>
                {/* add css inline component  */}
                <style>
                    {`.test-css{background-color: green;}`}
                </style>
            </Head>
            <div className="bg-white rounded-sm space-y-1">
                {isSelectedFilter && <div className="flex justify-end p-1 px-2">
                    <ArrowPathIcon className="h-6 w-6" onClick={onReset}></ArrowPathIcon>
                </div>
                }
                <div className="grid lg:flex w-full space-y-1 lg:space-y-0 lg:space-x-1">
                    {filters.map((item, index) => <DropdownMenu {...item} onChange={onChange} key={`item.${index}`}></DropdownMenu>)}
                </div>
            </div>
        </>
    )
}

List.layout = page => (
    <TopPageLayout children={page} />
)

export default List
