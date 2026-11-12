import { FolderIcon } from '@heroicons/react/24/solid';
import { Head, Link } from '@inertiajs/react';
import axios from 'axios';
import { useCallback, useEffect, useState } from 'react';
import Urls from '../../network/Urls';

export default function ImageManage({ selected, onSelect }: { selected: string[], onSelect: (value: string) => void }) {

  const [path, setPath] = useState('');
  const [data, setData] = useState({ files: [], directories: [], currentDirectory: '' });
  const { files, directories, currentDirectory } = data;

  const parts = currentDirectory.split('/');
  const currentFolder = parts[parts.length - 1];

  const getDirectories = useCallback(async (path: string) => {
    const response = await axios.get(Urls.adminhtml.fileManage, { params: { path: path } });
    setData(response.data);
  }, []);

  useEffect(() => {
    getDirectories(path);
  }, [path])

  return (
    <div className="p-4">
      <Head title="image manage"></Head>
      {/* <p className={'text-gray-600 text-lg italic'}>{currentDirectory}</p> */}
      {parts.length > 1 && (
        <div className="flex items-center mb-4">
          {parts.map((part, index) => (
            <div
              key={index}
              onClick={() => setPath(parts.slice(0, index + 1).join('/'))}
              // href={window.location.href}
              // data={{ path: parts.slice(0, index + 1).join('/') }}
              className={'text-gray-800 text-xl italic hover:underline font-semibold cursor-pointer'}
            >
              {part}{index < parts.length - 1 && '/'}
            </div>
          ))}
        </div>
      )}
      <h2 className='font-semibold text-lg text-blue-600 uppercase'>{currentFolder}</h2>
      <Directories directories={directories} onChangeDir={(value: string) => {
        setPath(value);
      }}></Directories>
      <Files files={files} selected={selected} onSelect={onSelect}></Files>
    </div>
  )
}

const Directories = ({ directories, onChangeDir }: { directories: string[]; onChangeDir: (path: string) => void }) => {

  const folderName = useCallback((directory: string) => {
    const parts = directory.split('/');
    return parts[parts.length - 1];
  }, [])

  if (!directories || directories.length === 0) {
    return <p className="text-red-600 text-lg italic mt-4">
      Không có thư mục nào trong thư mục này.
    </p>;
  }

  return (
    <div>
      <ul className="list-disc pl-5 space-y-2">
        {directories.map((dir, index) => (
          <div
            key={index}
            onClick={() => onChangeDir(dir)}
            className='font-semibold text-lg flex hover:underline cursor-pointer'
          >
            <FolderIcon className="h-5 w-5 inline-block mr-2" />
            {folderName(dir)}
          </div>
        ))}
      </ul>
    </div>
  )
}

const Files = ({ files, selected, onSelect }: { files: string[], selected: string[], onSelect: (value: string) => void }) => {
  if (!files || files.length === 0) {
    return <p className="text-red-600 text-lg italic mt-4">
      Không có hình ảnh nào trong thư mục này!
    </p>;
  }

  return (
    <div className="p-4 bg-gray-200 grid grid-cols-3 gap-4">
      {files.map((file, index) => (
        <div key={index} onClick={() => onSelect(file)} className={'cursor-pointer'}>
          <img
            src={file}
            alt=""
            className={`size-20 rounded-md object-cover ${selected?.includes(file) ? 'ring-2 ring-blue-700 ring-offset-2' : ''}`}
          />
        </div>
      ))}
    </div>
  )
}