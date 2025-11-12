import { Link } from "@inertiajs/react";

const InpageCategory = ({ categories = [] }) => {
    if (!categories.length) {
        return null;
    }

    // https://accreditly.io/articles/webkit-box-explained
    // https://viblo.asia/p/mot-vai-thu-thuat-css-ma-chinh-frontend-co-the-con-chua-biet-phan-8-OeVKBDaJlkW
    return (
        <div className="bg-white p-1 px-2 table" style={{
            display: 'inherit',
        }}>
            <p className="font-bold text-xl underline my-1">Danh sách chủ đề:</p>
            <div
                className='gap-2 overflow-x-scroll flex-row py-2 w-full
                [&::-webkit-scrollbar]:w-2
                [&::-webkit-scrollbar]:h-2
                [&::-webkit-scrollbar-track]:rounded-full
                [&::-webkit-scrollbar-track]:bg-gray-100
                [&::-webkit-scrollbar-thumb]:rounded-full
                [&::-webkit-scrollbar-thumb]:bg-gray-300
                dark:[&::-webkit-scrollbar-track]:bg-neutral-700
                dark:[&::-webkit-scrollbar-thumb]:bg-neutral-500'
                style={{
                    display: '-webkit-box',
                }}>
                {categories.map((item, index) => {
                    return <p
                        key={index.toString()}
                        className='bg-green-400 p-1 px-2 justify-center items-center rounded-md shadow-md shadow-blue-gray-500 inset-shadow-md inset-shadow-indigo-500' >
                        <Link
                            href={route('list', { cat: item.id })}
                            className='hover:underline text-lg text-white hover:text-black hover:font-bold'>{item.name}</Link>
                    </p>
                })}
            </div>
        </div>
    )
}

export default InpageCategory;
