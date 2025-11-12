import { Link, } from '@inertiajs/react';

const CategoryTop = ({ categories }) => {
    if (!categories) {
        return;
    }

    return (
        <div>
            <p className='text-xl underline'>Danh sách chủ đề chính:</p>
            <div
                className='gap-2 overflow-x-scroll flex-row py-2'
                style={{
                    display: '-webkit-box',
                }}>
                {categories.map((item, index) => {
                    return <p
                        key={index.toString()}
                        className='bg-green-400 p-1 px-2 justify-center items-center rounded-md' >
                        <Link 
                            href={route('list', { category: item.id })}
                            className='hover:text-red-400 hover:underline text-lg text-white'>{item.name}</Link>
                    </p>
                })}
            </div>
        </div>
    )
}

export default CategoryTop;
