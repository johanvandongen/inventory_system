import React, { useState } from 'react';

export default function (props) {
    const [img, setImg] = useState(0);
    return (
        <div className="relative h-32 flex justify-center">
            <img className="object-contain h-full w-full" src={ props.images[img] }></img>
            
            <p class="absolute p-1 top-0 left-0">{ img + 1 } / { props.images.length}</p>
            <button class="absolute p-1 left-0 top-1/2 transform -translate-y-1/2" onClick={() => setImg((prev) => (prev - 1) % props.images.length )}>&#10094;</button>
            <button class="absolute p-1 right-0 top-1/2 transform -translate-y-1/2" onClick={() => setImg((prev) => (prev + 1) % props.images.length )}>&#10095;</button>
        </div>
    );
}
