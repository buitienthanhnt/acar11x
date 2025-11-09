import { Link } from "@inertiajs/react";

const Tags = ({ tags }) => {
    if (!tags.length) {
        return null;
    }

    return (
        <div className="p-2 bg-white rounded-md">
            <p className="font-bold text-xl underline my-1">Liên kết:</p>
            <div className="space-x-1 md:space-x-2 flex">
                {tags.map(function (tag, index) {
                    return (
                        <Link href={route('tag', { value: tag.key })}
                            className="bg-blue-gray-500 px-2 py-1 rounded-md shadow-md shadow-blue-gray-400 hover:bg-light-green-600 hover:text-white hover:font-bold"
                            key={`tag-${index}`}
                        >
                            {tag.value}
                        </Link>
                    )
                })}
            </div>
        </div>
    )
}

export default Tags;
