import { FolderIcon, PlusCircleIcon, XCircleIcon } from '@heroicons/react/24/solid';
import { useForm } from '@inertiajs/react';
import axios from 'axios';
import { useCallback, useEffect, useState } from 'react';
import Urls from '../../network/Urls';

export default function FileManage({ selected, onSelect }: { selected: string[], onSelect: (value: string) => void }) {
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
      {currentFolder}
      <UploadFile currentFolder={currentFolder}></UploadFile>
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
    <div className="p-4 bg-gray-200 grid grid-cols-3 gap-4 rounded-md">
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

const UploadFile = ({ currentFolder, afterUpload }: { currentFolder: string, afterUpload?: () => void }) => {
  const { data, setData } = useForm({
    fileUpload: null,
  })

  const onUpload = useCallback(async () => {
    console.log(data);
    if (!data.fileUpload) {
      return;
    }
    /**
     * post thường sẽ không upload được file lên server
     * mà phải dùng: postForm
     * nếu không có thể dùng:
     * router.post(Urls.adminhtml.fileUpload, data) của inertiaJs nhưng sẽ bị điều hướng trang
     */
    await axios.postForm(Urls.adminhtml.fileUpload, {
      ...data,
      currentFolder
    });
    afterUpload?.();
  }, [data.fileUpload, data, currentFolder])

  return (
    <div className='flex flex-1 bg-blue-gray-50 rounded-md p-4 items-center space-x-2'>
      <div className=''>
        <div className='border border-gray-600 rounded-md p-2 flex space-x-2'>
          {data.fileUpload && <div>
            <XCircleIcon className='size-9 text-gray-500 hover:text-orange-600 cursor-pointer' onClick={() => setData('fileUpload', null)}></XCircleIcon></div>}
          <input
            type="file"
            multiple id="upload-file"
            /** @ts-ignore */
            onChange={e => setData('fileUpload', e?.target?.files as unknown as FileList)}
          />
        </div>
        {data.fileUpload && <div className='grid grid-cols-5 gap-2 py-4'>
          {
            (
              () => {
                let files: any[] = [];
                /**
                 * duyệt qua danh sách file upload cần dùng for loop
                 * vì nó không phải 1 mảng thuần túy nên không dùng .map() được.
                 * nó ở dạng object: FileList {0: File, 1: File, 2: File, ....}
                 */
                // @ts-ignore
                for (let i = 0; i < data.fileUpload.length; i++) {
                  files.push(<img src={URL.createObjectURL(data.fileUpload[i])} key={i} className='size-20 rounded-md' />);
                }
                return files;
              }
            )()
          }
        </div>
        }
      </div>

      <PlusCircleIcon className='size-9 text-gray-600 hover:text-black cursor-pointer' title='upload now' onClick={onUpload}></PlusCircleIcon>
    </div>
  )
}