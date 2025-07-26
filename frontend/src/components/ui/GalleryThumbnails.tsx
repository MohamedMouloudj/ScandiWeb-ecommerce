interface GalleryThumbnailsProps {
  images: { imageUrl: string }[];
  selectedIdx: number;
  onSelect: (idx: number) => void;
}

export default function GalleryThumbnails({
  images,
  selectedIdx,
  onSelect,
}: GalleryThumbnailsProps) {
  return (
    <div className="flex flex-col gap-2 overflow-y-auto max-h-[320px] md:max-h-[448px]">
      {images.map((img, idx) => (
        <img
          key={idx}
          src={img.imageUrl}
          alt="thumbnail"
          className={`w-16 h-16 object-contain rounded border cursor-pointer ${
            selectedIdx === idx
              ? "border-primary ring-1 ring-primary"
              : "border-gray-200"
          }`}
          onClick={() => onSelect(idx)}
        />
      ))}
    </div>
  );
}
