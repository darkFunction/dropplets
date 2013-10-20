# NSScotland 2013: Sunday 
- Sam
- darkFunction
- 2013/10/20
- NSScotland
- draft 

Notes from day two! 

### How to Get Hired by Black Pixel ###
- Who are Black Pixel?
	- Leading development company based in Seattle.
	- Interview questions for iOS and Mac developers ("Pascal criteria"):
		- Asynchronous networking
		- Multi-threaded CoreData
		- Issues with blocks
		- NSOperationQueue
		- NSURLNotification
		- CoreText
		- CoreGraphics
		- CoreAnimation
	
Becoming a better developer

- Sushi
	- Jiro Dreams of Sushi
		- Love your work
		- Constantly looking for areas of improvement
- Paper
	- Write down your plan to improve
		- Skills tree (Google for "everything a competent iOS developer needs to know"
		- Skills table - None/Beginner/Intermediate/Expert
- Stone
	- Software arch
	- oo concepts
	-scalability
	- Design pattersn

Debugging
Instrumentation
Scientific method
Code compression
Reductionism
Stop guessing

Knowledge 
Experience
Speed
Quality
Completeness
Rework

Simplicity
Test coverage
Technical debt
Code elegance
User experience
Support imapct
KISS

"Simple made easy" talk

The Practice of Learning
- Concrete experience
- Relective Observation
- Abstract conceptualisation
- Active experimentation

Plan. Sushi, Paper, Stone

### Core Audio (Gordon Murrison) ###
(Smoovie author)

- Music history
- MIDI
	- Simple protocol, key pressed, key released, how hard
- Digital audio
 40k, 80k (mono), 160k, bytes p s
 - PCI cards
 - COreAudio architecture

 use NSSound and AVaudioPlayer - high level, simple, fast, relatively advanced so you shoulnd't need to dig down
- Build a metronome
- Can't rely on NSTimer accuracy under CPU load
	- Audio file services to work directly with queues for accurate timing
- Audio Units
	- Plugins which process audio
	- DSP processes input, set properties before run, params affect during run
	- Have about 10ms in the unit to process data
	- Audio Unit Graphs
		- Nodes represent units
		- Sine wave generator, chained units, eg reverb, delay

### Virtually Understanding Memory (Jamie Montgomerie) ###
- Virtual memory on OSX
- Virtual memory on iOS- iOS does have virtual memory system 
- Application memory
	- 64 bit, twice as big address space (more memory than could physically exist)
	- An app doesn't get access to all the space immediately.
	- Space is split into chunks 4k big, managed by OS kernel.
	app must use kernel apis to request memory access

-virtual mem
	- operates on 4k pages
	set of cont pages called mem region 
	- memory map
	use vmmap to look at virtual memory map
		- type , range, size, permissions, maximum permisssions, share mode (PRIvate, Copy On Write, SHared, aliased, filesytem bath to backing file if any or maybe debugging info)
	all pages no necc assoc with real phys mem
	pages backed by real ram are called resident
	paes that are in rambut not on disk are dirtyi, eg malloc
	file backed regions read lazily
	page fault is reading mem which is not resident
	or when copy on write region is modified

	vmmap -resident -dirty
	
	instruments only tracks memory alloced through alloc
	doesn't track virtual mem
	eg, CALayer objects are just handles but exist much larger in real ram
	some apis bypass apis and work directly with VM system, eg, NS/UIImage
	new instruments "all anonymous vm" can show vm use
	framework code and resources can be shared between resources and only loaded into ram once
	same with mem mapped files, only read once
	alloced regions from malloc are not really allocated until they are used
	efficient system

	system accounts for pages as active inactive free
	underpresssure
		- removes clean cahced files and purgable writable regions
		writes dirty sections of shared files to dis

Using the VM system for yourself
	Allocating anonymous memory
		just use malloc
	Memory mapping a file
		nsdata
			nsdatareadingmappedifsafe/always/uncached
		bsd nmap capi
		include sys/mman.h
		void *mmap(addr, len..., filedescriptor)
		munmap
Purable regions
	special kind of memory, not file backed, discarable
	good for data that is fast and easy to recreate
	nscache based on this
		nspurgabledata / nspurablecontent
		can use these objects in nscache, works well
when to use VM sys directly
	mem map files
		great for large sparse dara
	share mem between procs
		posix semaphores
when not to
	files smaller than 4k
		just malloc
	mapping can fragment memory space
	surprisingly easy to run out of address space in 32 bit
	xpc or distributed objects might be a better interproc method of sharing mem

ios not that diff to osx
only thing missing is dynamic paging on non disk backed data
can use the tools, eg, vmmap, just fine on the simulator

top
	rprvt

apple docs 
	memory usage performace guidelines
	man vmmap

### Accessibility (Matt Gemmel) ###
Haptic in the future
Select, navigate, activate
keyboard- slide finger, other finger to select
search, label, curtain
	can actually relabel items manually and implement your own a.ids
saves battery
scrub cancel
magic tap
the rotor - changes interaction mode
why should you care
	devices are a lifeline
	tools of inclusion and independance		
285 million people worldwide (visually impaired)
90% in developing world
one api
	UIAccessibilityAPI
	label is breif name ie, play
	hint is what the item does, plays the current song
acccessibility inspector in simulator is a bit shit, test on device
can make voiceover say something at specific times, read content
skip animations for voice over?
don't rely on gestures- voiceover will own them
label your bloody icons
"blind people won't use my app" - blind people *will* use your app
colour id
	- matching clothes
	- is it charged?
	- did i leave the lights on
games
	board/card/word/puzzle
trust the tech
simple, quick, really matters


### Seeing The Bigger Picture (Simon Wolf) ###
History of Airplay
Multiple screens and airplay mirroring
Look (at start) and listen for screens becoming active/inactive
Dealing with overscan


